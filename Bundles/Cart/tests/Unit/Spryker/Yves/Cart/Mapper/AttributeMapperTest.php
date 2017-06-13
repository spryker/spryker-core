<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\Cart\Mapper;

use Spryker\Yves\Cart\Mapper\CartItemsAttributeMapper;
use Spryker\Yves\Cart\Mapper\CartItemsAvailabilityMapper;

/**
 * @group Unit
 * @group Spryker
 * @group Yves
 * @group Cart
 * @group Mapper
 * @group AttributeMapperTest
 */
class AttributeMapperTest extends CartItemsMapperBaseTest
{

    /**
     * @return void
     */
    public function testBuildMap()
    {
        $subject = new CartItemsAttributeMapper(
            $this->buildProductClientMock(),
            new CartItemsAvailabilityMapper($this->buildProductAvailabilityClientMock())
        );
        $result = $subject->buildMap($this->getItems());

        $this->assertArrayHasKey('170_28516206', $result);

        $attributes = $result['170_28516206'];

        $this->assertArrayHasKey('color', $attributes);
        $this->assertSame(3, count($attributes['color']));

        $this->assertSame(1, $this->countSelectedAttributes($attributes['color']));

        $this->assertArrayHasKey('processor_frequency', $attributes);
        $this->assertSame(3, count($attributes['processor_frequency']));

        $this->assertSame(1, $this->countSelectedAttributes($attributes['processor_frequency']));
    }

}
