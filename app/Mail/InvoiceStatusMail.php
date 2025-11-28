<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Invoice;

class InvoiceStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $messageContent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice, string $messageContent)
    {
        $this->invoice = $invoice;
        $this->messageContent = $messageContent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Mise à jour de votre facture {$this->invoice->invoice_number}")
            ->view('emails.invoice_status')
            ->with([
                'invoice' => $this->invoice,
                'messageContent' => $this->messageContent,
            ]);
    }
}
