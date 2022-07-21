<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfigurationWishlist;

use Codeception\Actor;
use InvalidArgumentException;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 * @method \Spryker\Zed\ProductConfigurationWishlist\Business\ProductConfigurationWishlistFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductConfigurationWishlistBusinessTester extends Actor
{
    use _generated\ProductConfigurationWishlistBusinessTesterActions;

    /**
     * @param array<string, mixed> $data
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function encodeJson(array $data): string
    {
        $result = $this->getLocator()->utilEncoding()->service()->encodeJson($data);
        if ($result === null) {
            throw new InvalidArgumentException('Null value returned, invalid $data');
        }

        return $result;
    }
}
