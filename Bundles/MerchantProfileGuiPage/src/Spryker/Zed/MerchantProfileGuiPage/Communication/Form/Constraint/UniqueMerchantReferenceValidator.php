<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication\Form\Constraint;

use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @method \Spryker\Zed\MerchantProfileGuiPage\Communication\MerchantProfileGuiPageCommunicationFactory getFactory()
 */
class UniqueMerchantReferenceValidator extends AbstractConstraintValidator
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

        $merchantTransfer = $this->findMerchantByReference($value);

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

    /**
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    protected function findMerchantByReference(string $merchantReference): ?MerchantTransfer
    {
        $merchantCriteriaFilterTransfer = new MerchantCriteriaFilterTransfer();
        $merchantCriteriaFilterTransfer->setMerchantReference($merchantReference);

        return $this->getFactory()->getMerchantFacade()->findOne($merchantCriteriaFilterTransfer);
    }
}
