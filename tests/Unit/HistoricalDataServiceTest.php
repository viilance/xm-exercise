<?php

namespace Tests\Unit;

use App\Services\CompanyService;
use App\Services\HistoricalDataService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\MockObject\Exception;
use Tests\TestCase;

class HistoricalDataServiceTest extends TestCase
{
    private HistoricalDataService $historicalDataService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->historicalDataService = new HistoricalDataService();
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function testGetHistoricalDataReturnsArray()
    {
        $mockCompanyService = $this->createMock(CompanyService::class);
        $mockCompanyService->method('getCompanyNameBySymbol')->willReturn('Company Name');

        $this->historicalDataService = new HistoricalDataService();

        Http::fake([
            '*' => Http::response([
                'prices' => [
                    [
                        'date' => now()->subDay()->timestamp,
                        'open' => 173.02,
                        'high' => 174.03,
                        'low' => 171.9,
                        'close' => 173.56,
                        'volume' => 53672700,
                        'adjclose' => 173.56,
                    ],
                ],
            ], 200),
        ]);

        $result = $this->historicalDataService->getHistoricalData([
            'company_symbol' => 'AAPL',
            'start_date' => '2023-01-01',
            'end_date' => now()->format('Y-m-d'),
            'email' => 'test@example.com',
        ], $mockCompanyService);

        $this->assertArrayHasKey('prices', $result);
        $this->assertCount(1, $result['prices']);

        $price = $result['prices'][0];

        $this->assertEquals([
            'open' => 173.02,
            'high' => 174.03,
            'low' => 171.9,
            'close' => 173.56,
            'volume' => 53672700,
            'adjclose' => 173.56,
        ], Arr::except($price, 'date'));
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function testGetHistoricalDataThrowsExceptionOnFailedRequest()
    {
        $mockCompanyService = $this->createMock(CompanyService::class);
        $mockCompanyService->method('getCompanyNameBySymbol')->willReturn('Company Name');

        $this->historicalDataService = new HistoricalDataService();

        Http::fake([
            '*' => Http::response([], 400),
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to fetch historical data');

        $this->historicalDataService->getHistoricalData([
            'company_symbol' => 'AAPL',
            'start_date' => '2022-12-01',
            'end_date' => '2022-12-31',
            'email' => 'test@example.com',
        ], $mockCompanyService);
    }


}
