<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    /**
     * @return void
     */
    public function testHomePageIsWorkingCorrectly(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSeeText('Welcome to the XM Exercise');
    }

    /**
     * @return void
     */
    public function testFormFieldsArePresent(): void
    {
        $response = $this->get('/');

        $response->assertSee('company_symbol');
        $response->assertSee('start_date');
        $response->assertSee('end_date');
        $response->assertSee('email');
    }

    /**
     * @return void
     */
    public function testFormValidationErrorsAreDisplayed(): void
    {
        $response = $this->post('/submit', [
            'company_symbol' => '',
            'start_date' => '',
            'end_date' => '',
            'email' => '',
        ]);

        $response->assertSessionHasErrors([
            'company_symbol',
            'start_date',
            'end_date',
            'email',
        ]);
    }

    /**
     * @return void
     */
    public function testCorrectErrorMessageIsDisplayedForEachField(): void
    {
        $response = $this->post('/submit', [
            'company_symbol' => '',
            'start_date' => '',
            'end_date' => '',
            'email' => '',
        ]);

        $response->assertSessionHasErrors([
            'company_symbol' => 'The company symbol field is required.',
            'start_date' => 'The start date field is required.',
            'end_date' => 'The end date field is required.',
            'email' => 'The email field is required.',
        ]);
    }
}
