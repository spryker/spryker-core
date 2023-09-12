<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Form\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ServicePointTransfer;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ServiceValidator implements ServiceValidatorInterface
{
    /**
     * @var string
     */
    protected const VALIDATION_MESSAGE_SERVICE_IS_NOT_CHOSEN = 'Until Service is not chosen, Service Point won`t be saved either.';

    /**
     * @uses \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Expander\ServiceProductOfferFormExpander::FIELD_SERVICE_POINT
     *
     * @var string
     */
    protected const FIELD_SERVICE_POINT = ServicePointTransfer::ID_SERVICE_POINT;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer> $serviceTransfers
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     *
     * @return void
     */
    public function validate(ArrayObject $serviceTransfers, ExecutionContextInterface $context): void
    {
        if (count($serviceTransfers) > 0) {
            return;
        }

        $context->buildViolation(static::VALIDATION_MESSAGE_SERVICE_IS_NOT_CHOSEN)
            ->atPath(static::FIELD_SERVICE_POINT)
            ->addViolation();
    }
}
