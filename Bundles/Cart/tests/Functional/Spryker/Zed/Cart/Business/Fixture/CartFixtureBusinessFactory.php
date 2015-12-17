<?php

namespace Functional\Spryker\Zed\Cart\Fixture;

use Spryker\Shared\Config;
use Spryker\Zed\Cart\Business\CartBusinessFactory;
use Spryker\Zed\Cart\CartConfig;

class CartFixtureBusinessFactory extends CartBusinessFactory
{

    public function getConfig()
    {
        return new CartConfig(Config::getInstance(), $this->getLocator());
    }

}
