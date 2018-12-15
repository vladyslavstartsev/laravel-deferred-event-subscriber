<?php

declare(strict_types=1);

namespace VladyslavStartsev\DeferredEventSubscriber;

interface EventSubscriberInterface
{
    public static function getEventListenersList(): array;
}
