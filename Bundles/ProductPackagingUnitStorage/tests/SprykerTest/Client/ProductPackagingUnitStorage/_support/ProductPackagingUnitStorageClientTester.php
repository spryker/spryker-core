<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductPackagingUnitStorage;

use Codeception\Actor;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Client\ProductPackagingUnitStorage\ProductPackagingUnitStorageDependencyProvider;

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
class ProductPackagingUnitStorageClientTester extends Actor
{
    use _generated\ProductPackagingUnitStorageClientTesterActions;

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $productPackagingUnitStorageToStorageClientBridge
     * @param array|null $content
     *
     * @return void
     */
    public function setStorageMock(MockObject $productPackagingUnitStorageToStorageClientBridge, ?array $content): void
    {
        $productPackagingUnitStorageToStorageClientBridge->method('get')->willReturn($content);
        $this->setDependency(ProductPackagingUnitStorageDependencyProvider::CLIENT_STORAGE, $productPackagingUnitStorageToStorageClientBridge);
    }
}
