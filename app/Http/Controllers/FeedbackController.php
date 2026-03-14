<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{
    /**
     * Handle kritik & saran form submission and email admin
     */
    public function send(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Persist feedback to DB first so user never sees an error
        $feedback = \App\Models\Feedback::create([
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'message' => $data['message'],
            'status' => 'pending',
        ]);

        $to = env('ADMIN_EMAIL', 'alftihrizky65@gmail.com');
        $subject = 'Kritik & Saran Website GEOTERRAID';

        $body = "Nama: " . ($data['name'] ?? 'Anonim') . "\n";
        $body .= "Email: " . ($data['email'] ?? 'Tidak disediakan') . "\n\n";
        $body .= "Pesan:\n" . $data['message'];

        try {
            Mail::raw($body, function ($message) use ($to, $subject, $data) {
                $message->from(env('MAIL_FROM_ADDRESS', 'no-reply@geoterraid.local'), env('MAIL_FROM_NAME', 'No Reply - SIP'));
                $message->to($to)->subject($subject);
                if (!empty($data['email'])) {
                    $message->replyTo($data['email'], $data['name'] ?? null);
                }
            });

            $feedback->update(['status' => 'sent', 'attempts' => $feedback->attempts + 1]);
            return back()->with('success', 'Terima kasih — masukan Anda telah dikirim ke Admin SIP.');
        } catch (\Exception $e) {
            Log::error('FeedbackController@send error: ' . $e->getMessage());

            // Update DB record as failed (but user still sees success)
            $feedback->update(['status' => 'failed', 'attempts' => $feedback->attempts + 1, 'last_error' => $e->getMessage()]);

            // Also append to fallback log for manual inspection
            try {
                $logPath = storage_path('logs/feedbacks.log');
                $entry = "[" . now()->toDateTimeString() . "] (DB id: " . $feedback->id . ") Name: " . ($data['name'] ?? 'Anonim') . " | Email: " . ($data['email'] ?? 'N/A') . " | Message: " . str_replace(["\r", "\n"], [' ', ' '], $data['message']) . " | MailError: " . $e->getMessage() . PHP_EOL;
                file_put_contents($logPath, $entry, FILE_APPEND | LOCK_EX);
                Log::info('Feedback saved to ' . $logPath);

                return back()->with('success', 'Terima kasih — masukan Anda telah disimpan; Admin SIP akan diberitahukan segera.');
            } catch (\Exception $e2) {
                Log::error('FeedbackController fallback save error: ' . $e2->getMessage());
                return back()->with('error', 'Terjadi kesalahan saat menyimpan masukan. Silakan coba lagi nanti.');
            }
        }
    }
}
