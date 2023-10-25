<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\StrategyResolver;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Expander\ServicePointAddressExpanderInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\ShipmentTypeServicePointValidatorInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
interface ShipmentTypeServicePointStrategyResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Expander\ServicePointAddressExpanderInterface
     */
    public function resolveAddressExpander(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): ServicePointAddressExpanderInterface;

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\ShipmentTypeServicePointValidatorInterface
     */
    public function resolveValidator(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): ShipmentTypeServicePointValidatorInterface;
}
