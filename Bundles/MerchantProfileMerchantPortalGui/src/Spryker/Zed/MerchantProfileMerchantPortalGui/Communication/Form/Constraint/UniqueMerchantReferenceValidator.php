<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @method \Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\MerchantProfileMerchantPortalGuiCommunicationFactory getFactory()
 */
class UniqueMerchantReferenceValidator extends AbstractConstraintValidator
{
    /**
     * @param string $value
     * @param \Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\Constraint\UniqueMerchantReference $constraint
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
        $merchantCriteriaTransfer = new MerchantCriteriaTransfer();
        $merchantCriteriaTransfer->setMerchantReference($merchantReference);

        return $this->getFactory()->getMerchantFacade()->findOne($merchantCriteriaTransfer);
    }
}
