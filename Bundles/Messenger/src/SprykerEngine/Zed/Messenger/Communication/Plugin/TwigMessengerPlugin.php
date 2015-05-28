<?php

namespace SprykerEngine\Zed\Messenger\Communication\Plugin;

use SprykerEngine\Shared\Messenger\Communication\Plugin\AbstractTwigMessengerPlugin;
use SprykerEngine\Zed\Kernel\Communication\Factory;
use SprykerEngine\Zed\Kernel\Locator;

class TwigMessengerPlugin extends AbstractTwigMessengerPlugin
{

    public function __construct(Factory $factory, Locator $locator)
    {
    }

}
