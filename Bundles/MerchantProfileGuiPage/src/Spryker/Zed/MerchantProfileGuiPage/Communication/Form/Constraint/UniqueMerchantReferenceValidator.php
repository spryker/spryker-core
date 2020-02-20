<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueMerchantReferenceValidator extends ConstraintValidator
{
    /**
     * @param string $value
     * @param \Spryker\Zed\MerchantProfileGuiPage\Communication\Form\Constraint\UniqueMerchantReference $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value) {
            return;
        }

        $merchantTransfer = $constraint->findMerchantByReference($value);

        if ($merchantTransfer === null) {
            return;
        }

        if ($constraint->getCurrentMerchantId() && $merchantTransfer->getIdMerchant() === $constraint->getCurrentMerchantId()) {
            return;
        }

        $this->context
            ->buildViolation($constraint->getMessage())
            ->addViolation();
    }
}
