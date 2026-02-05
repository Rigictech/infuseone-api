<?php

namespace App\Mail\Transport;

use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\RawMessage;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Envelope;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Mime\Email;

class BrevoTransport implements TransportInterface
{
    protected string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Send the message via Brevo API
     */
    public function send(RawMessage $message, ?Envelope $envelope = null): ?SentMessage
    {
        if (!$message instanceof Email) {
            throw new \InvalidArgumentException('Only Symfony\Component\Mime\Email messages are supported.');
        }

        $email = $message;

        $to = [];
        foreach ($email->getTo() as $address) {
            // Ensure a name is set, even if it's an empty string
            $to[] = [
                'email' => $address->getAddress(),
                'name' => $address->getName() ?: '',  // Use empty string if no name
            ];
        }

        $from = $email->getFrom()[0] ?? null;
        if (!$from) {
            throw new \InvalidArgumentException('Email must have a From address.');
        }

        $data = [
            'sender' => [
                'name' => $from->getName() ?: config('mail.from.name'),
                'email' => $from->getAddress(),
            ],
            'to' => $to,
            'subject' => $email->getSubject(),
            'htmlContent' => $email->getHtmlBody() ?: nl2br($email->getTextBody() ?? ' '),
        ];

        // Send via Brevo API
        $response = Http::withHeaders([
            'api-key' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', $data);

        // Check if we got a valid response (optional logging)
        if ($response->successful()) {
            return new SentMessage($message, $envelope ?? Envelope::create($message));
        }

        // Optionally log failure response here
        \Log::error('Brevo API Error: ' . $response->body());

        return null;
    }

    public function __toString(): string
    {
        return 'brevo';
    }
}
