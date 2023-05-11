<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompanyHistoricalDataMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $companyName;
    public string $startDate;
    public string $endDate;

    /**
     * Create a new message instance.
     */
    public function __construct($companyName, $startDate, $endDate)
    {
        $this->companyName = $companyName;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return CompanyHistoricalDataMail
     */
    public function build(): CompanyHistoricalDataMail
    {
        return $this->subject($this->companyName)
            ->view('emails.company_historical_data')
            ->with([
                'startDate' => $this->startDate,
                'endDate' => $this->endDate
            ]);
    }
}
