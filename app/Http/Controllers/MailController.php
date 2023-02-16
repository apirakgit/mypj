<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderMail;
class MailController extends Controller
{
    public function sendEmail()
    {
      $details = [
        'title' => 'Mail form Evolt',
        'body' => 'This is for testing mail'
      ];

      Mail::to("apirak.p@codegears.co.th")->send(new OrderMail($details));
      return "Email Send";
    }


}
