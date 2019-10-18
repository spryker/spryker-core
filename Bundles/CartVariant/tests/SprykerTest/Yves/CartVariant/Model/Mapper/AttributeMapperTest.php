<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\CartVariant\Model\Mapper;

use Spryker\Yves\CartVariant\Mapper\CartItemsAttributeMapper;
use Spryker\Yves\CartVariant\Mapper\CartItemsAvailabilityMapper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group CartVariant
 * @group Model
 * @group Mapper
 * @group AttributeMapperTest
 * Add your own group annotations below this line
 */
class AttributeMapperTest extends CartItemsMapperBaseTest
{
    /**
     * @return void
     */
    public function testBuildMap()
    {
        $subject = new CartItemsAttributeMapper(
            $this->createProductClientBridge('attribute.json'),
            new CartItemsAvailabilityMapper($this->createAvailabilityStorageClientBridge('availability.json'))
        );
        $result = $subject->buildMap($this->getItems());

        $this->assertArrayHasKey('170_28516206', $result);

        $attributes = $result['170_28516206'];

        $this->assertArrayHasKey('color', $attributes);
        $this->assertSame(3, count($attributes['color']));

        $this->assertSame(1, $this->countSelectedAttributes($attributes['color']));

        $this->assertArrayHasKey('processor_frequency', $attributes);
        $this->assertCount(3, $attributes['processor_frequency']);

        $this->assertSame(1, $this->countSelectedAttributes($attributes['processor_frequency']));
    }

    /**
     * @return void
     */
    public function testBuildNestedMap()
    {
        $subject = new CartItemsAttributeMapper(
            $this->createProductClientBridge('attributeNested.json'),
            new CartItemsAvailabilityMapper($this->createAvailabilityStorageClientBridge('availabilityNested.json'))
        );
        $result = $subject->buildMap($this->getNestedItems());

        $this->assertArrayHasKey('112_312526171', $result);

        $attributes = $result['112_312526171'];

        $this->assertArrayHasKey('chassis_type', $attributes);
        $this->assertSame(1, count($attributes['chassis_type']));
    }
}
