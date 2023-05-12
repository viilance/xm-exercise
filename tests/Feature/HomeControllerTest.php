<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class HomeControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex(): void
    {
        Http::fake([
            '*' => Http::response(['Symbol' => 'AAPL', 'Company Name' => 'Apple Inc.'], 200),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testIndexReturnsViewWithCompanies()
    {
        $fakeCompanies = [
            [
                "Symbol" => "AAPL",
                "Company Name" => "Apple Inc."
            ],
            [
                "Symbol" => "GOOG",
                "Company Name" => "Google Inc."
            ]
        ];

        Http::fake([
            '*' => Http::response($fakeCompanies, 200),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('companies', [
            "AAPL" => "Apple Inc.",
            "GOOG" => "Google Inc."
        ]);
    }

    public function testIndexRedirectsOnError()
    {
        Http::fake([
            '*' => Http::response([], 500),
        ]);

        $response = $this->get('/');

        $response->assertRedirect('/');
        $response->assertSessionHasErrors(['error']);
    }

    public function testSubmitRedirectsOnSuccessfulDataFetch()
    {
        Http::fake([
            'https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data*' => Http::response([
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
            ]),
        ]);

        $response = $this->post('/submit', [
            'company_symbol' => 'AAPL',
            'start_date' => '2023-01-01',
            'end_date' => now()->format('Y-m-d'),
            'email' => 'test@test.com',
        ]);

        $response->assertRedirect('/historical-data');
        $response->assertSessionHas('historicalData', function ($historicalData) {
            return $historicalData['prices'][0]['adjclose'] === 173.56;
        });
    }

    public function testSubmitRedirectsBackOnError()
    {
        Http::fake([
            'https://pkgstore.datahub.io/core/nasdaq-listings/*' => Http::response([
                [
                    "Symbol" => "AAPL",
                    "Company Name" => "Apple Inc."
                ]
            ], 200),
            'https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data' => Http::response([], 500)
        ]);

        $formData = [
            'company_symbol' => 'AAPL',
            'start_date' => '2023-05-01',
            'end_date' => '2023-05-31',
            'email' => 'test@example.com'
        ];

        $response = $this->post('/submit', $formData);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors();
    }
}
