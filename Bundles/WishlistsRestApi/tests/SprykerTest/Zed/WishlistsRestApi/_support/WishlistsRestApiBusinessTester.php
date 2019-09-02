<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\WishlistsRestApi;

use Codeception\Actor;
use Spryker\Zed\WishlistsRestApi\Business\WishlistsRestApiFacadeInterface;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class WishlistsRestApiBusinessTester extends Actor
{
    use _generated\WishlistsRestApiBusinessTesterActions;

    /**
     * @return \Spryker\Zed\WishlistsRestApi\Business\WishlistsRestApiFacadeInterface
     */
    public function getWishlistsRestApiFacade(): WishlistsRestApiFacadeInterface
    {
        /**
         * @var \Spryker\Zed\WishlistsRestApi\Business\WishlistsRestApiFacadeInterface
         */
        $facade = $this->getFacade();

        return $facade;
    }
}
