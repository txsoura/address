<?php

namespace App\Events;

use App\Models\Address;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddressCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var Address */
    public $address;

    /**
     * Create a new event instance.
     *
     * @param Address $address
     * @return void
     */
    public function __construct(Address $address)
    {
        $this->address = $address;
    }
}
