<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\Cart\Mapper;

use Spryker\Yves\Cart\Mapper\CartItemsAttributeAndAvailabilityMapper;
use Spryker\Yves\Cart\Mapper\CartItemsAttributeMapper;
use Spryker\Yves\Cart\Mapper\CartItemsAvailabilityMapper;

/**
 * @group Unit
 * @group Spryker
 * @group Yves
 * @group Cart
 * @group Mapper
 * @group CartItemsAndAvailabilityMapperTest
 */
class CartItemsAndAvailabilityMapperTest extends CartItemsMapperBaseTest
{

    /**
     * @return void
     */
    public function testBuildMap()
    {
        $subject = new CartItemsAttributeAndAvailabilityMapper($this->getAvailabilityMapper(), $this->getAttributesMapper());
        $result = $subject->buildMap($this->getItems());

        $this->assertArrayHasKey('attributes', $result);
        $attributesData = $result['attributes'];

        $attributes = $attributesData['170_28516206'];

        $this->assertArrayHasKey('color', $attributes);
        $this->assertSame(3, count($attributes['color']));

        $this->assertSame(1, $this->countSelectedAttributes($attributes['color']));

        $this->assertArrayHasKey('processor_frequency', $attributes);
        $this->assertSame(3, count($attributes['processor_frequency']));

        $this->assertSame(1, $this->countSelectedAttributes($attributes['processor_frequency']));

        $this->assertArrayHasKey('availability', $result);
        $availabilityData = $result['availability'];

        $availability = $availabilityData['170_28516206'];
        $this->assertArrayHasKey('concreteProductAvailableItems', $availability);
        $this->assertSame(true, (bool)$availability['concreteProductAvailableItems']);
        $this->assertArrayHasKey('concreteProductsAvailability', $availability);
        $this->assertSame(20, $availability['concreteProductsAvailability']);
    }

    /**
     * @return \Spryker\Yves\Cart\Mapper\CartItemsAvailabilityMapper
     */
    protected function getAvailabilityMapper()
    {
        return new CartItemsAvailabilityMapper($this->buildProductAvailabilityClientMock());
    }

    /**
     * @return \Spryker\Yves\Cart\Mapper\CartItemsAttributeMapper
     */
    protected function getAttributesMapper()
    {
        return new CartItemsAttributeMapper($this->buildProductClientMock());
    }

}
