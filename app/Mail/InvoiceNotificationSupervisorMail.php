<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceNotificationSupervisorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;

    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    public function build()
    {
        return $this->subject("Nouvelle facture à valider")
            ->view('emails.invoice_supervisor_notification')
            ->with([
                'invoiceNumber' => $this->invoice->invoice_number,
                'consultantName' => $this->invoice->consultant->name . ' ' . $this->invoice->consultant->last_name,
            ]);
    }
}
