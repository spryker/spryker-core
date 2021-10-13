<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DiscountsRestApi;

use Spryker\Glue\DiscountsRestApi\Processor\Mapper\DiscountMapper;
use Spryker\Glue\DiscountsRestApi\Processor\Mapper\DiscountMapperInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\DiscountsRestApi\DiscountsRestApiConfig getConfig()
 */
class DiscountsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\DiscountsRestApi\Processor\Mapper\DiscountMapperInterface
     */
    public function createDiscountMapper(): DiscountMapperInterface
    {
        return new DiscountMapper();
    }
}
