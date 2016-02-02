<?php

namespace Functional\Spryker\Zed\Cart\Fixture;

use Spryker\Zed\Cart\Business\CartBusinessFactory;
use Spryker\Zed\Cart\CartConfig;

class CartFixtureBusinessFactory extends CartBusinessFactory
{

    /**
     * @return \Spryker\Zed\Cart\CartConfig
     */
    public function getConfig()
    {
        return new CartConfig();
    }

}
