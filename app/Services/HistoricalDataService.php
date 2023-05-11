<?php

namespace App\Services;

use App\Mail\CompanyHistoricalDataMail;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HistoricalDataService
{
    const HISTORICAL_DATA_URL = 'https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data';

    /**
     * @throws Exception
     */
    public function getHistoricalData($validatedData,CompanyService $companyService)
    {
        $response = Http::withHeaders([
            'X-RapidApi-Key' => config('services.rapidapi.key'),
            'X-RapidApi-Host' => config('services.rapidapi.host')
        ])->get(self::HISTORICAL_DATA_URL, [
            'symbol' => $validatedData['company_symbol']
        ]);

        if ($response->failed()) {
            throw new Exception('Failed to fetch historical data');
        }

        if ($response->successful()) {
            $historicalData = $response->json();

            $startTimestamp = strtotime($validatedData['start_date']);
            $endTimestamp = strtotime($validatedData['end_date']);

            $filteredPrices = array_filter(
                $historicalData['prices'], function ($price) use ($startTimestamp, $endTimestamp) {
                $priceDate = $price['date'];
                return $priceDate >= $startTimestamp && $priceDate <= $endTimestamp;
            });

            $historicalData['prices'] = array_values($filteredPrices);

            $companyName = $companyService->getCompanyNameBySymbol($validatedData['company_symbol']);

            try {
                Mail::to($validatedData['email'])
                    ->send(
                        new CompanyHistoricalDataMail(
                            $companyName,
                            $validatedData['start_date'],
                            $validatedData['end_date']
                        )
                    );
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }

            return $historicalData;
        }
    }
}
