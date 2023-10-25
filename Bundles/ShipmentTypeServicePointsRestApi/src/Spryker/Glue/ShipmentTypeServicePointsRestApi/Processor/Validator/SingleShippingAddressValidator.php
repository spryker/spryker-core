<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Creator\RestErrorMessageCreatorInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 *             Use {@link \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\MultiShippingAddressValidator} instead.
 */
class SingleShippingAddressValidator implements ShippingAddressValidatorInterface
{
    /**
     * @var \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Creator\RestErrorMessageCreatorInterface
     */
    protected RestErrorMessageCreatorInterface $restErrorMessageCreator;

    /**
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Creator\RestErrorMessageCreatorInterface $restErrorMessageCreator
     */
    public function __construct(RestErrorMessageCreatorInterface $restErrorMessageCreator)
    {
        $this->restErrorMessageCreator = $restErrorMessageCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    public function validate(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestErrorCollectionTransfer
    {
        $restErrorCollectionTransfer = new RestErrorCollectionTransfer();
        if ($restCheckoutRequestAttributesTransfer->getServicePoints()->count() === 0) {
            $restErrorCollectionTransfer->addRestError(
                $this->restErrorMessageCreator->createServicePointNotProvidedErrorMessage(),
            );
        }

        if ($restCheckoutRequestAttributesTransfer->getServicePoints()->count() > 1) {
            $restErrorCollectionTransfer->addRestError(
                $this->restErrorMessageCreator->createOnlyOneServicePointShouldBeSelectedErrorMessage(),
            );
        }

        return $restErrorCollectionTransfer;
    }
}
