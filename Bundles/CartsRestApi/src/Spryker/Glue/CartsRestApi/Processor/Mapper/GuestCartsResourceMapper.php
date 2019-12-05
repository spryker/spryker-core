<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Mapper;

use Spryker\Glue\CartsRestApi\CartsRestApiConfig;

class GuestCartsResourceMapper extends CartsResourceMapper implements CartsResourceMapperInterface
{
    /**
     * @return string
     */
    protected function getCartResourceName(): string
    {
        return CartsRestApiConfig::RESOURCE_GUEST_CARTS;
    }

    /**
     * @return string
     */
    protected function getCartItemResourceName(): string
    {
        return CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS;
    }
}
