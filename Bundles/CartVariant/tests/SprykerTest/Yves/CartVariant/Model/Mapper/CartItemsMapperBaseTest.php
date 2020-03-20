<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\CartVariant\Model\Mapper;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\StorageAvailabilityTransfer;
use Spryker\Client\AvailabilityStorage\AvailabilityStorageClient;
use Spryker\Client\AvailabilityStorage\AvailabilityStorageClientInterface;
use Spryker\Client\Product\ProductClient;
use Spryker\Client\Product\ProductClientInterface;
use Spryker\Yves\CartVariant\Dependency\Client\CartVariantToAvailabilityStorageClientBridge;
use Spryker\Yves\CartVariant\Dependency\Client\CartVariantToAvailabilityStorageClientBridgeInterface;
use Spryker\Yves\CartVariant\Dependency\Client\CartVariantToProductClientBridge;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group CartVariant
 * @group Model
 * @group Mapper
 * @group CartItemsMapperBaseTest
 * Add your own group annotations below this line
 */
class CartItemsMapperBaseTest extends Unit
{
    /**
     * @param string $jsonFileToLoad
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Product\ProductClientInterface
     */
    protected function buildProductClientMock(string $jsonFileToLoad): ProductClientInterface
    {
        $mock = $this->getMockBuilder(ProductClient::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAttributeMapByIdProductAbstractForCurrentLocale'])->getMock();

        $mock->method('getAttributeMapByIdProductAbstractForCurrentLocale')
            ->willReturn(json_decode(file_get_contents(__DIR__ . '/json/' . $jsonFileToLoad), true));

        return $mock;
    }

    /**
     * @param string $jsonFileToLoad
     *
     * @return \Spryker\Yves\CartVariant\Dependency\Client\CartVariantToProductClientBridge
     */
    protected function createProductClientBridge(string $jsonFileToLoad): CartVariantToProductClientBridge
    {
        return new CartVariantToProductClientBridge($this->buildProductClientMock($jsonFileToLoad));
    }

    /**
     * @param string $jsonFileToLoad
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\AvailabilityStorage\AvailabilityStorageClientInterface
     */
    protected function buildProductAvailabilityClientMock(string $jsonFileToLoad): AvailabilityStorageClientInterface
    {
        $mock = $this->getMockBuilder(AvailabilityStorageClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock->method('getProductAvailabilityByIdProductAbstract')
            ->willReturn($this->getAvailabilityTransfer($jsonFileToLoad));

        return $mock;
    }

    /**
     * @param string $jsonFileToLoad
     *
     * @return \Spryker\Yves\CartVariant\Dependency\Client\CartVariantToAvailabilityStorageClientBridgeInterface
     */
    protected function createAvailabilityStorageClientBridge(string $jsonFileToLoad): CartVariantToAvailabilityStorageClientBridgeInterface
    {
        return new CartVariantToAvailabilityStorageClientBridge($this->buildProductAvailabilityClientMock($jsonFileToLoad));
    }

    /**
     * @param string $jsonFileToLoad
     *
     * @return \Generated\Shared\Transfer\StorageAvailabilityTransfer
     */
    protected function getAvailabilityTransfer(string $jsonFileToLoad): StorageAvailabilityTransfer
    {
        $transfer = new StorageAvailabilityTransfer();
        $transfer->fromArray(
            json_decode(file_get_contents(__DIR__ . '/json/' . $jsonFileToLoad), true),
            true
        );

        return $transfer;
    }

    /**
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getItems(): ArrayObject
    {
        $item = new ItemTransfer();
        $item->setSku('170_28516206');
        $item->setId(166);

        $item2 = new ItemTransfer();
        $item2->setSku('170_28549472');
        $item2->setId(167);

        return new ArrayObject([$item, $item2]);
    }

    /**
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getNestedItems(): ArrayObject
    {
        $item = new ItemTransfer();
        $item->setSku('112_312526171');
        $item->setId(131);

        $item2 = new ItemTransfer();
        $item2->setSku('112_306918001');
        $item2->setId(132);

        return new ArrayObject([$item, $item2]);
    }

    /**
     * @param array $attributes
     *
     * @return int
     */
    protected function countSelectedAttributes(array $attributes): int
    {
        $total = 0;

        foreach ($attributes as $attribute) {
            if ($attribute['selected'] === true) {
                $total++;
            }
        }

        return $total;
    }
}
