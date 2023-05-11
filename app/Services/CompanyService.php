<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class CompanyService
{
    const NASDAQ_LISTINGS_URL = 'https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json';

    private ?array $companies;

    public function __construct()
    {
        $this->companies = null;
    }

    /**
     * @throws Exception
     */
    public function getCompanies(): ?array
    {
        if ($this->companies) {
            return $this->companies;
        }

        $response = Http::get(self::NASDAQ_LISTINGS_URL);

        if ($response->failed()) {
            throw new Exception('Failed to fetch companies');
        }

        $companiesData = $response->json();

        $this->companies = array_column($companiesData, 'Company Name', 'Symbol');

        return $this->companies;
    }

    /**
     * @throws Exception
     */
    public function getCompanyNameBySymbol($symbol)
    {
        $companies = $this->getCompanies();

        return $companies[$symbol] ?? null;
    }
}
