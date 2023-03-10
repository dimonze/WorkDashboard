<?php

namespace App\Command\Helpers\Utility;

use PhpImap\Mailbox;


class MailService
{
    private string $host;
    private string $port;
    private string $username;
    private string $password;

    public function __construct(string $host, string $port, string $username, string $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }

    public function getMessages(): array
    {
        try {
            $mailbox = new Mailbox(
                sprintf('{%s:%s/imap/ssl}', $this->host, $this->port),
                $this->username,
                $this->password
            );
            $mailIds = array_reverse($mailbox->searchMailbox("ALL"));
            $messages = [];
            foreach ($mailIds as $mailId) {
                $mail = $mailbox->getMail($mailId);
                $messages[] = [
                    'subject' => $mail->subject,
                    'from' => $mail->fromAddress,
                    'date' => $mail->date,
                    'body' => $mail->textHtml,
                ];
            }

            return $messages;
        } catch (\Exception $e) {
            echo $e;
            // Обработка ошибок
        }
        return [];
    }
}