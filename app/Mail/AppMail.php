<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppMail extends Mailable
{
    use Queueable, SerializesModels;

    public $template;
    public $subject;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($template,$subject,$data)
    {
        $this->template = $template ?? "emails.student.creationError";
        $this->subject = $subject;
        $this->data = $data;       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('example@example.com')
        ->view('emails.orders.shipped')
        ->subject($this->subject)
        ->with($this->data);
    }
}
