<?php

namespace App\Http\Controllers;

use App\Services\CompanyService;
use App\Services\HistoricalDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    protected CompanyService $companyService;
    protected HistoricalDataService $historicalDataService;

    public function __construct(CompanyService $companyService, HistoricalDataService $historicalDataService)
    {
        $this->companyService = $companyService;
        $this->historicalDataService = $historicalDataService;
    }

    public function index()
    {
        try {
            $companies = $this->companyService->getCompanies();
            return view('home', ['companies' => $companies]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('home')
                ->withErrors(['error' => __('messages.fetch_error')]);
        }

    }

    public function submit(Request $request)
    {
        $validatedData = $request->validate([
            'company_symbol' => 'required',
            'start_date' => 'required|date|before_or_equal:end_date|before_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:today',
            'email' => 'required|email',
        ]);

        try {
            $historicalData = $this->historicalDataService->getHistoricalData($validatedData, $this->companyService);
            return redirect('/historical-data')->with('historicalData', $historicalData);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->withErrors(__('messages.fetch_error'))->withInput();
        }
    }
}
