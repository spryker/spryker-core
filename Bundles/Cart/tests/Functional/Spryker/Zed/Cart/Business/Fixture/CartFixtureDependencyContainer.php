<?php

namespace Functional\Spryker\Zed\Cart\Fixture;

use Spryker\Shared\Config;
use Spryker\Zed\Cart\Business\CartDependencyContainer;
use Spryker\Zed\Cart\CartConfig;

class CartFixtureDependencyContainer extends CartDependencyContainer
{

    public function getConfig()
    {
        return new CartConfig(Config::getInstance(), $this->getLocator());
    }

}
