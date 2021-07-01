<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCertificate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    public $row;
    /**
     * @var string
     */
    public $emailBody;
    /**
     * @var string
     */
    public $certificatePath;

    /**
     * Create a new message instance.
     *
     * @param array $row
     * @param string $emailBody
     * @param string $certificatePath
     */
    public function __construct(array $row, string $emailBody, string $certificatePath)
    {
        //
        $this->row = $row;
        $this->emailBody = $emailBody;
        $this->certificatePath = $certificatePath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->html($this->emailBody)
            ->subject("Congratulations! Your Skillplay Certificate is here")
            ->cc('info@skillplay.co')
            ->attach($this->certificatePath, [
                'as' => 'Certificate.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
