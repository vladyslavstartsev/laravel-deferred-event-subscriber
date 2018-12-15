# Easily defer subscriber class resolution in laravel 5

This package offers way to defer subscriber class resolution in Laravel 5.

# The problem
Once I was working o the app that heavily uses event subscribers.
What I noticed is, if I've requested index page,
I resolve all of my subscribers with their dependencies,
which becomes kinda heady once you have, for example,
10 subscribers each of them uses 3 unique dependencies,
it's at least 30 dependencies if you only look at
the first children of this graph (there can be more hidden).

## Install

You can install the package via composer:

``` bash
composer require vladyslavstartsev/deferred-event-subscriber
```

You're done.

## Usage

Usage can be described in those steps

- create some event

```php

<?php

declare(strict_types=1);

namespace App\Events;

use App\Order;
use Illuminate\Queue\SerializesModels;

class OrderShipped
{
    use SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}

```

- implement `EventSubscriberInterface` on the subscriber of it

```php

<?php

declare(strict_types=1);

namespace App\Events\Subscribers;

class DeferredOrdersEventSubscriber implements \App\Events\EventSubscriberInterface
{
    /**
     * @return string[]
     */
    public static function getEventListenersList(): array
    {
        return [
            \App\Events\OrderShipped::class => 'onOrderShipped',
        ];
    }

    public function onOrderShipped(\App\Events\OrderShipped $event): void
    {
        //do something here
    }
}
```

- create new or extend the default `App\Providers\EventServiceProvider`
with `VladyslavStartsev\DeferredEventSubscriber\AbstractDeferredEventServiceProvider`

```php

<?php

declare(strict_types=1);

namespace App\Providers;

class EventServiceProvider extends AbstractDeferredEventServiceProvider
{
    /**
     * @var string[]
     */
    protected $deferredSubscribers = [
        \App\Events\Subscribers\DeferredOrdersEventSubscriber::class,
    ];
}

```

That's it!