<?php

namespace App\Http\Controllers;

use Twilio\Rest\Client;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    public function sendWhatsAppMessage()
    {
        /*  $twilioSid = config('app.twilio_sid');
        $twilioToken = config('app.twilio_auth_token');
        $twilioWhatsAppNumber = config('app.twilio_whatsapp_number'); */
        $twilioWhatsAppNumber = "whatsapp:+14155238886"; //env("TWILIO_WHATSAPP_NUMBER");
        $sid = env("TWILIO_SID");
        $token = env("TWILIO_AUTH_TOKEN");
        $recipientNumber = "whatsapp:+33646189501"; // Replace with the recipient's phone number in WhatsApp format (e.g., "whatsapp:+1234567890")
        $message = "Hello from Twilio WhatsApp API HUMANIISANTE APP! 🚀";

        $twilio = new Client($sid, $token);

        try {
            $twilio->messages->create(
                $recipientNumber,
                [
                    "from" => $twilioWhatsAppNumber,
                    "body" => $message,
                ]
            );

            return response()->json(['message' => 'WhatsApp message sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
