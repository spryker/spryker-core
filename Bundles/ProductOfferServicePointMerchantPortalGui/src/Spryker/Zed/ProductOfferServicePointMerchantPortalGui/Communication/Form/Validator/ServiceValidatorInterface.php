<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Form\Validator;

use ArrayObject;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

interface ServiceValidatorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer> $serviceTransfers
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     *
     * @return void
     */
    public function validate(ArrayObject $serviceTransfers, ExecutionContextInterface $context): void;
}
