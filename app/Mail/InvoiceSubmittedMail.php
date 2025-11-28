<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;

    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    public function build()
    {
        return $this->subject("Votre facture a été enregistrée")
            ->view('emails.invoice_submitted')
            ->with([
                'invoiceNumber' => $this->invoice->invoice_number,
                'supervisorName' => $this->invoice->consultant->supervisor_name,
            ]);
    }
}
