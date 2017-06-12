<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Cart\Mapper;

use Generated\Shared\Transfer\StorageProductTransfer;

class CartItemsAttributeAndAvailabilityMapper implements CartItemsMapperInterface
{
    const AVAILABILITY = 'availability';

    /**
     * @var \Spryker\Yves\Cart\Mapper\CartItemsAttributeMapper
     */
    protected $attributeMapper;

    /**
     * @var \Spryker\Yves\Cart\Mapper\CartItemsAvailabilityMapper
     */
    protected $availabilityMapper;

    /**
     * @param \Spryker\Yves\Cart\Mapper\CartItemsAvailabilityMapper $availabilityMapper
     * @param \Spryker\Yves\Cart\Mapper\CartItemsAttributeMapper $attributeMapper
     */
    public function __construct(
        CartItemsAvailabilityMapper $availabilityMapper,
        CartItemsAttributeMapper $attributeMapper
    ) {

        $this->availabilityMapper = $availabilityMapper;
        $this->attributeMapper = $attributeMapper;

    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return array
     */
    public function buildMap($items)
    {
        $map = [];

        $map[static::AVAILABILITY] = $this->availabilityMapper->buildMap($items);
        $map[StorageProductTransfer::ATTRIBUTES] = $this->attributeMapper->buildMap($items);

        return $map;
    }

}
