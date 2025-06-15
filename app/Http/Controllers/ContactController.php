<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:50',
            'phone' => 'required|string|regex:/^\+?[0-9\s\-\(\)]{10,15}$/',
            'email' => 'required|email|max:255',
        ]);

        try {
            // Отправка письма
            Mail::to(config('mail.admin_email'))->send(new ContactFormMail(
                $request->name,
                $request->phone,
                $request->email
            ));

            return response()->json([
                'success' => true,
                'message' => 'Сообщение успешно отправлено'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error sending contact form email: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Ошибка при отправке сообщения'
            ], 500);
        }
    }
}
