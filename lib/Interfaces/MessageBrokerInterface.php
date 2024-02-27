<?php

namespace NextBasket\Interfaces;

interface MessageBrokerInterface
{
    public function __construct(array $config);

    public function send(string $message);
}
