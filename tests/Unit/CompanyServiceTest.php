<?php

namespace Tests\Unit;

use App\Services\CompanyService;
use Exception;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CompanyServiceTest extends TestCase
{
    use WithFaker;

    /**
     * A basic unit test example.
     *
     * @return void
     * @throws Exception
     */
    public function testGetCompanies(): void
    {
        Http::fake([
            '*' => Http::response([['Symbol' => 'AAPL', 'Company Name' => 'Apple Inc.']], 200),
        ]);

        $companyService = new CompanyService();
        $companies = $companyService->getCompanies();

        $this->assertIsArray($companies);
        $this->assertEquals('Apple Inc.', $companies['AAPL']);
    }

    /**
     * @throws Exception
     */
    public function testGetCompaniesReturnsArray()
    {
        Http::fake([
            CompanyService::NASDAQ_LISTINGS_URL => Http::response($this->generateCompaniesData(), 200),
        ]);

        $companyService = new CompanyService();
        $companies = $companyService->getCompanies();

        $this->assertIsArray($companies);
    }

    /**
     * @throws Exception
     */
    public function testGetCompaniesReturnsCorrectStructure()
    {
        $mockData = $this->generateCompaniesData();

        Http::fake([
            CompanyService::NASDAQ_LISTINGS_URL => Http::response($mockData, 200),
        ]);

        $companyService = new CompanyService();
        $companies = $companyService->getCompanies();

        foreach ($mockData as $data) {
            $this->assertArrayHasKey($data['Symbol'], $companies);
            $this->assertEquals($data['Company Name'], $companies[$data['Symbol']]);
        }
    }

    /**
     * @throws Exception
     */
    public function testGetCompanyNameBySymbolReturnsCorrectName()
    {
        $mockData = $this->generateCompaniesData();

        Http::fake([
            CompanyService::NASDAQ_LISTINGS_URL => Http::response($mockData, 200),
        ]);

        $companyService = new CompanyService();
        $companyName = $companyService->getCompanyNameBySymbol($mockData[0]['Symbol']);

        $this->assertEquals($mockData[0]['Company Name'], $companyName);
    }

    /**
     * @throws Exception
     */
    public function testGetCompanyNameBySymbolReturnsNullForInvalidSymbol()
    {
        $mockData = $this->generateCompaniesData();

        Http::fake([
            CompanyService::NASDAQ_LISTINGS_URL => Http::response($mockData, 200),
        ]);

        $companyService = new CompanyService();
        $companyName = $companyService->getCompanyNameBySymbol('INVALID_SYMBOL');

        $this->assertNull($companyName);
    }

    /**
     * Generate mock data for company listings
     *
     * @param int $count
     * @return array
     */
    private function generateCompaniesData(int $count = 10): array
    {
        $companies = [];

        for ($i = 0; $i < $count; $i++) {
            $companies[] = [
                "Company Name" => $this->faker->company,
                "Financial Status" => "N",
                "Market Category" => "G",
                "Round Lot Size" => 100.0,
                "Security Name" => $this->faker->words(3, true),
                "Symbol" => strtoupper($this->faker->randomLetter . $this->faker->randomNumber(2)),
                "Test Issue" => "N"
            ];
        }

        return $companies;
    }
}
