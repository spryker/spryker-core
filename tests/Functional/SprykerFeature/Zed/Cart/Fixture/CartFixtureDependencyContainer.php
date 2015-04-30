<?php

namespace Functional\SprykerFeature\Zed\Cart\Fixture;

use SprykerFeature\Zed\Cart\Business\CartDependencyContainer;
use SprykerFeature\Zed\Cart\Business\CartSettings;

class CartFixtureDependencyContainer extends CartDependencyContainer
{
    protected function getSettings()
    {
        return  new CartSettings($this->getLocator());
    }
}
 