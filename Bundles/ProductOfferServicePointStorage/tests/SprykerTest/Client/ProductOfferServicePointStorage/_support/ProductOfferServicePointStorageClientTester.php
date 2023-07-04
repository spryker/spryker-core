<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductOfferServicePointStorage;

use Codeception\Actor;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Client\ProductOfferServicePointStorage\ProductOfferServicePointStorageClientInterface getClient()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductOfferServicePointStorageClientTester extends Actor
{
    use _generated\ProductOfferServicePointStorageClientTesterActions;

    /**
     * @param string $key
     * @param array<string, mixed> $data
     *
     * @return void
     */
    public function setToStorage(string $key, array $data): void
    {
        $this->getLocator()->storage()->client()->set($key, $this->encodeJson($data));
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return string|null
     */
    protected function encodeJson(array $data): ?string
    {
        return $this->getLocator()->utilEncoding()->service()->encodeJson($data);
    }
}
