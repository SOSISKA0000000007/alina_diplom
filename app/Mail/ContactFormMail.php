<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $phone;
    public $email;

    public function __construct($name, $phone, $email)
    {
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
    }

    public function build()
    {
        return $this->subject('Новое сообщение с контактной формы')
            ->view('emails.contact')
            ->with([
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
            ]);
    }
}
