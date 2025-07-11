<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductStorage;

use Codeception\Actor;
use Codeception\Stub;
use ReflectionClass;
use Spryker\Zed\ProductStorage\Business\ProductStorageFacade;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\AbstractProductConcreteStorageListener;
use Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\AbstractProductStorageListener;

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
 *
 * @SuppressWarnings(\SprykerTest\Zed\ProductStorage\PHPMD)
 */
class ProductStorageCommunicationTester extends Actor
{
    use _generated\ProductStorageCommunicationTesterActions;

    /**
     * @var string
     */
    public const PARAM_PROJECT = 'PROJECT';

    /**
     * @var string
     */
    public const PROJECT_SUITE = 'suite';

    /**
     * @return bool
     */
    public function isSuiteProject(): bool
    {
        if (getenv(static::PARAM_PROJECT) === static::PROJECT_SUITE) {
            return true;
        }

        return false;
    }

    /**
     * @return void
     */
    public function cleanUpProcessedAbstractProductIds()
    {
        $refClass = new ReflectionClass(AbstractProductStorageListener::class);
        $property = $refClass->getProperty('publishedProductAbstractIds');
        $property->setAccessible(true);
        $property->setValue([]);

        $property = $refClass->getProperty('unpublishedProductAbstractIds');
        $property->setAccessible(true);
        $property->setValue([]);
    }

    /**
     * @return void
     */
    public function cleanUpProcessedConcreteProductIds()
    {
        $refClass = new ReflectionClass(AbstractProductConcreteStorageListener::class);
        $property = $refClass->getProperty('publishedProductConcreteIds');
        $property->setAccessible(true);
        $property->setValue([]);

        $property = $refClass->getProperty('unpublishedProductConcreteIds');
        $property->setAccessible(true);
        $property->setValue([]);
    }

    /**
     * @return (\object&\PHPUnit\Framework\MockObject\MockObject)|(\Spryker\Zed\ProductStorage\Business\ProductStorageFacade&\object&\PHPUnit\Framework\MockObject\MockObject)|(\Spryker\Zed\ProductStorage\Business\ProductStorageFacade&\object&\PHPUnit\Framework\MockObject\MockObject&\object&\PHPUnit\Framework\MockObject\MockObject)
     */
    public function mockProductStorageFacade(): ProductStorageFacade
    {
        return Stub::makeEmpty(ProductStorageFacade::class);
    }
}
