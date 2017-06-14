<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\Cart\Mapper;

use Spryker\Yves\Cart\Mapper\CartItemsAvailabilityMapper;

/**
 * @group Unit
 * @group Spryker
 * @group Yves
 * @group Cart
 * @group Mapper
 * @group AvailabilityMapperTest
 */
class AvailabilityMapperTest extends CartItemsMapperBaseTest
{

    /**
     * @return void
     */
    public function testBuildMap()
    {
        $subject = new CartItemsAvailabilityMapper($this->buildProductAvailabilityClientMock('availability.json'));
        $result = $subject->buildMap($this->getItems());

        $this->assertArrayHasKey('170_28516206', $result);

        $availability = $result['170_28516206'];

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

}
