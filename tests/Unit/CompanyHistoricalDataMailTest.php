<?php

namespace Tests\Unit;

use App\Mail\CompanyHistoricalDataMail;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CompanyHistoricalDataMailTest extends TestCase
{
    /** @test */
    public function it_builds_the_right_view_with_correct_data()
    {
        Mail::fake();

        $companyName = 'Test Company';
        $startDate = '2022-01-01';
        $endDate = '2022-12-31';

        Mail::to('test@example.com')->send(new CompanyHistoricalDataMail($companyName, $startDate, $endDate));

        Mail::assertSent(CompanyHistoricalDataMail::class);

        Mail::assertSent(CompanyHistoricalDataMail::class, function ($mail) use ($companyName, $startDate, $endDate) {
            $mail->build();

            $this->assertEquals($companyName, $mail->subject);

            $this->assertTrue($mail->hasTo('test@example.com'));

            $this->assertEquals($startDate, $mail->viewData['startDate']);
            $this->assertEquals($endDate, $mail->viewData['endDate']);

            return true;
        });
    }
}
