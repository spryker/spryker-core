<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\Cart\Mapper;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\StorageAvailabilityTransfer;
use Spryker\Client\Availability\AvailabilityClient;
use Spryker\Client\Product\ProductClient;

/**
 * @group Unit
 * @group Spryker
 * @group Yves
 * @group Cart
 * @group Mapper
 * @group CartItemsMapperBaseTest
 */
class CartItemsMapperBaseTest extends Test
{

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject | \Spryker\Client\Product\ProductClientInterface
     */
    protected function buildProductClientMock()
    {
        $mock = $this->getMockBuilder(ProductClient::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAttributeMapByIdProductAbstractForCurrentLocale'])->getMock();

        $mock->method('getAttributeMapByIdProductAbstractForCurrentLocale')
            ->willReturn(\json_decode(file_get_contents(__DIR__ . '/attribute.json'), true));
        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject | \Spryker\Client\Availability\AvailabilityClientInterface
     */
    protected function buildProductAvailabilityClientMock()
    {
        $mock = $this->getMockBuilder(AvailabilityClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock->method('getProductAvailabilityByIdProductAbstract')
            ->willReturn($this->getAvailabilityTransfer());
        return $mock;
    }

    /**
     * @return \Generated\Shared\Transfer\StorageAvailabilityTransfer
     */
    protected function getAvailabilityTransfer()
    {
        $transfer = new StorageAvailabilityTransfer();
        $transfer->fromArray(
            \json_decode(file_get_contents(__DIR__ . '/availability.json'), true),
            true
        );
        return $transfer;
    }

    /**
     * @return array
     */
    protected function getItems()
    {
        $item = new ItemTransfer();
        $item->setSku('170_28516206');
        $item->setId(166);

        $item2 = new ItemTransfer();
        $item2->setSku('170_28549472');
        $item2->setId(167);

        return [$item, $item2];
    }

    /**
     * @param array $attributes
     *
     * @return int
     */
    protected function countSelectedAttributes(array $attributes)
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
