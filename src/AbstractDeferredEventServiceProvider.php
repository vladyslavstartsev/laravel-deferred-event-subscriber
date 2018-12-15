<?php

declare(strict_types=1);

namespace VladyslavStartsev\DeferredEventSubscriber;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Support\Facades\Event;

abstract class AbstractDeferredEventServiceProvider extends EventServiceProvider
{
    /** @var EventSubscriberInterface[] */
    protected $deferredSubscribers = [];

    public function boot(): void
    {
        parent::boot();

        foreach ($this->deferredSubscribers as $subscriber) {
            foreach ($subscriber::getEventListenersList() as $event => $listener) {
                Event::listen($event, $subscriber . '@' . $listener);
            }
        }
    }
}