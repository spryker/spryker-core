<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\Rule\ShipmentTypeServicePoint;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Creator\RestErrorMessageCreatorInterface;

class CustomerDataShipmentTypeServicePointValidatorRule implements ShipmentTypeServicePointValidatorRuleInterface
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
    public function validate(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestErrorCollectionTransfer {
        $restErrorCollectionTransfer = new RestErrorCollectionTransfer();
        if (!$restCheckoutRequestAttributesTransfer->getCustomer()) {
            return $restErrorCollectionTransfer;
        }

        if ($this->hasRequiredCustomerData($restCheckoutRequestAttributesTransfer)) {
            return $restErrorCollectionTransfer;
        }

        return $restErrorCollectionTransfer->addRestError(
            $this->restErrorMessageCreator->createCustomerDataMissingErrorMessage(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return bool
     */
    protected function hasRequiredCustomerData(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): bool
    {
        $restCustomerTransfer = $restCheckoutRequestAttributesTransfer->getCustomerOrFail();

        return $restCustomerTransfer->getFirstName()
            && $restCustomerTransfer->getLastName()
            && $restCustomerTransfer->getSalutation();
    }
}
