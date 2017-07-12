<?php

namespace App\Mail;

use App\Proposition;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Chencha\Share\ShareFacade as Share;

class PropositionApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $proposition;
    public $shareLinks;

    /**
     * Create a new message instance.
     *
     * @param Proposition $prop
     */
    public function __construct(Proposition $prop)
    {
        $this->proposition = $prop;
        $this->shareLinks = Share::load(route('proposition', [$prop->id()]), $prop->propositionSort())->services();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(trans('messages.emails.approved-proposition.subject'))
            ->markdown('emails.proposition.approved');
    }
}
