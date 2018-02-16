<?php

namespace App\Mail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class QuotationRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $r =[];
    public $user='';


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user,$data)
    {
        $this->r = $data;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('quotation');
    }
}
