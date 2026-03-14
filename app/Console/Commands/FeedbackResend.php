<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Feedback;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class FeedbackResend extends Command
{
    protected $signature = 'feedback:resend {--limit=50}';
    protected $description = 'Attempt to resend pending/failed feedbacks to admin';

    public function handle()
    {
        $limit = (int) $this->option('limit');
        $items = Feedback::whereIn('status', ['pending', 'failed'])->orderBy('created_at')->limit($limit)->get();

        if ($items->isEmpty()) {
            $this->info('No pending feedbacks to resend.');
            return 0;
        }

        foreach ($items as $f) {
            try {
                $to = env('ADMIN_EMAIL', 'alftihrizky65@gmail.com');
                $subject = 'Kritik & Saran Website GEOTERRAID (Resend)';
                $body = "Nama: " . ($f->name ?? 'Anonim') . "\n";
                $body .= "Email: " . ($f->email ?? 'Tidak disediakan') . "\n\n";
                $body .= "Pesan:\n" . $f->message;

                Mail::raw($body, function ($message) use ($to, $subject, $f) {
                    $message->from(env('MAIL_FROM_ADDRESS', 'no-reply@geoterraid.local'), env('MAIL_FROM_NAME', 'No Reply - SIP'));
                    $message->to($to)->subject($subject . ' (#' . $f->id . ')');
                    if (!empty($f->email)) {
                        $message->replyTo($f->email, $f->name ?? null);
                    }
                });

                $f->update(['status' => 'sent', 'attempts' => $f->attempts + 1, 'last_error' => null]);
                $this->info('Sent feedback #' . $f->id);
            } catch (\Exception $e) {
                $f->update(['status' => 'failed', 'attempts' => $f->attempts + 1, 'last_error' => $e->getMessage()]);
                Log::error('FeedbackResend send failed for #' . $f->id . ': ' . $e->getMessage());
                $this->error('Failed #' . $f->id . ': ' . $e->getMessage());
            }
        }

        return 0;
    }
}
