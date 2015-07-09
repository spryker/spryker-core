<?php

namespace Functional\SprykerFeature\Zed\Cart\Fixture;

use SprykerEngine\Shared\Config;
use SprykerFeature\Zed\Cart\Business\CartDependencyContainer;
use SprykerFeature\Zed\Cart\CartConfig;

class CartFixtureDependencyContainer extends CartDependencyContainer
{

    public function getConfig()
    {
        return new CartConfig(Config::getInstance(), $this->getLocator());
    }

}
