<?php

namespace SprykerEngine\Zed\Messenger\Communication;

class MessengerDependencyContainer
{
    public function createFoo()
    {
        $foo = new \stdClass();

        $foo->bar = 'ism';

        return $foo;
    }
}