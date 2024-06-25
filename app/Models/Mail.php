<?php
//App\Models\Mail.php
declare(strict_types=1);

namespace App\Models;

class Mail
{
    private string $senderEmail;
    private string $senderName;
    private array $recipients;
    private string $subject;
    private string $body;

    private string $contactTemplate = __DIR__ . "/../EmailTemplates/ContactEmail.html";

    public function __construct(string $senderEmail, string $senderName, array $recipients, string $subject, string $message, string $type)
    {
        $this->senderEmail = $senderEmail;
        $this->senderName = $senderName;
        $this->recipients = $recipients;
        $this->subject = $subject;
        $this->body = $message;
        if ($type === "contact") {
            $this->body = file_get_contents($this->contactTemplate);
            $this->body = str_replace(["[SUBJECT]", "[MESSAGE]", "[SENDER_NAME]", "[SENDER_EMAIL]"], [$subject, $message, $senderName, $senderEmail], $this->body);
        }
    }

    public function getSenderEmail(): string
    {
        return $this->senderEmail;
    }

    public function getSenderName(): string
    {
        return $this->senderName;
    }

    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getHTMLBody(): string
    {
        return $this->body;
    }

    public function getPlainTextBody(): string
    {
        return strip_tags($this->body);
    }
}
