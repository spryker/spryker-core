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
use Spryker\Yves\Cart\Mapper\CartItemsAttributeMapper;

/**
 * @group Unit
 * @group Spryker
 * @group Yves
 * @group Cart
 * @group Mapper
 * @group AttributeMapperTest
 */
class AttributeMapperTest extends Test
{

    /**
     * @return void
     */
    public function testBuildMap()
    {
        $subject = new CartItemsAttributeMapper($this->buildProductClientMock(), $this->buildProductAvailabilityClientMock());
        $result = $subject->buildMap($this->getItems());

        $this->assertArrayHasKey('170_28516206', $result);

        $productData = $result['170_28516206'];

        $this->assertArrayHasKey('attributes', $productData);
        $this->assertArrayHasKey('availability', $productData);

        $attributes = $productData['attributes'];

        $this->assertArrayHasKey('color', $attributes);
        $this->assertSame(3, count($attributes['color']));

        $this->assertSame(1, $this->countSelectedAttributes($attributes['color']));

        $this->assertArrayHasKey('processor_frequency', $attributes);
        $this->assertSame(3, count($attributes['processor_frequency']));

        $this->assertSame(1, $this->countSelectedAttributes($attributes['processor_frequency']));

        $availability = $productData['availability'];
        $this->assertArrayHasKey('concreteProductAvailableItems', $availability);
        $this->assertSame(true, (bool)$availability['concreteProductAvailableItems']);
        $this->assertArrayHasKey('concreteProductsAvailability', $availability);
        $this->assertSame(20, $availability['concreteProductsAvailability']);
    }

    /**
     * @param array $attributes
     *
     * @return int
     */
    protected function countSelectedAttributes(array $attributes)
    {
        $total = 0;

        foreach ($attributes as $selected) {
            if ($selected === true) {
                $total++;
            }
        }

        return $total;
    }

    /**
     * @return array
     */
    protected function getItems()
    {
        $item = new ItemTransfer();
        $item->setSku('170_28516206');
        $item->setId(166);

        return [$item];
    }

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

}
