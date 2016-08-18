<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Cart\Business\Fixture;

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
