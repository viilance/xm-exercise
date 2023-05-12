<?php

namespace Tests\Feature;

use Tests\TestCase;

class HistoricalDataPageTest extends TestCase
{
    /**
     * Test historical data page.
     *
     * @return void
     */
    public function testHistoricalDataPageIsWorkingCorrectly(): void
    {
        $response = $this->get('/historical-data');

        $response->assertStatus(200);
        $response->assertSeeText('Historical Data');
    }

    public function testHistoricalDataTableIsPresent()
    {
        $response = $this->get('/historical-data');

        $response->assertStatus(200);
        $response->assertSee('<table class="styled-table">', false);
    }

    public function testShowMoreButtonIsPresent()
    {
        $response = $this->get('/historical-data');

        $response->assertStatus(200);
        $response->assertSee('<button id="showMoreBtn">', false);
    }

    public function testCorrectMessageWhenNoData()
    {
        $response = $this->get('/historical-data');

        $response->assertStatus(200);
        $response->assertSee('No data available');
    }
}
