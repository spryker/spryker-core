<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\CartVariant\Model\Mapper;

use Spryker\Yves\CartVariant\Mapper\CartItemsAvailabilityMapper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group CartVariant
 * @group Model
 * @group Mapper
 * @group AvailabilityMapperTest
 * Add your own group annotations below this line
 */
class AvailabilityMapperTest extends CartItemsMapperBaseTest
{
    /**
     * @return void
     */
    public function testBuildMap()
    {
        $subject = new CartItemsAvailabilityMapper($this->createAvailabilityStorageClientBridge('availability.json'));
        $result = $subject->buildMap($this->getItems());

        $this->assertArrayHasKey('170_28516206', $result);

        $availability = $result['170_28516206'];

        $this->assertArrayHasKey('concreteProductAvailableItems', $availability);
        $this->assertSame(true, (bool)$availability['concreteProductAvailableItems']);
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
