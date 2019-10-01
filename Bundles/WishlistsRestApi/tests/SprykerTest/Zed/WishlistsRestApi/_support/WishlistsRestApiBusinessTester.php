<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\WishlistsRestApi;

use Codeception\Actor;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\WishlistsRestApi\Business\WishlistsRestApiFacadeInterface;

/**
 * Inherited Methods
 *
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
         * @var \Spryker\Zed\WishlistsRestApi\Business\WishlistsRestApiFacadeInterface $facade
         */
        $facade = $this->getFacade();

        return $facade;
    }

    /**
     * @param int $idCustomer
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getWishlistByName(int $idCustomer, string $name): WishlistTransfer
    {
        return $this->getWishlistFacade()->getWishlistByName(
            (new WishlistTransfer())
                ->setName($name)
                ->setFkCustomer($idCustomer)
        );
    }
}
