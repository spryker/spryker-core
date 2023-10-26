<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\StrategyResolver;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Mapper\QuoteMapperInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
interface QuoteMapperStrategyResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Mapper\QuoteMapperInterface
     */
    public function resolve(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): QuoteMapperInterface;
}
