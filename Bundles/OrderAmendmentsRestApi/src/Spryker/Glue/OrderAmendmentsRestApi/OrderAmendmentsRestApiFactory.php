<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrderAmendmentsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\OrderAmendmentsRestApi\Processor\Mapper\CartReorderRequestMapper;
use Spryker\Glue\OrderAmendmentsRestApi\Processor\Mapper\CartReorderRequestMapperInterface;
use Spryker\Glue\OrderAmendmentsRestApi\Processor\Mapper\RestCartAttributesMapper;
use Spryker\Glue\OrderAmendmentsRestApi\Processor\Mapper\RestCartAttributesMapperInterface;

class OrderAmendmentsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\OrderAmendmentsRestApi\Processor\Mapper\RestCartAttributesMapperInterface
     */
    public function createRestCartAttributesMapper(): RestCartAttributesMapperInterface
    {
        return new RestCartAttributesMapper();
    }

    /**
     * @return \Spryker\Glue\OrderAmendmentsRestApi\Processor\Mapper\CartReorderRequestMapperInterface
     */
    public function createCartReorderRequestMapper(): CartReorderRequestMapperInterface
    {
        return new CartReorderRequestMapper();
    }
}
