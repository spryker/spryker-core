<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication\Form\Constraint;

use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Communication\Validator\AbstractValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @method \Spryker\Zed\MerchantProfileGuiPage\Communication\MerchantProfileGuiPageCommunicationFactory getFactory()
 */
class UniqueEmailValidator extends AbstractValidator
{
    protected const ERROR_MESSAGE_PROVIDED_EMAIL_IS_ALREADY_USED = 'Email is already used.';

    /**
     * Checks if the passed email is unique.
     *
     * @param string $email
     * @param \Symfony\Component\Validator\Constraint $uniqueEmailConstraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($email, Constraint $uniqueEmailConstraint): void
    {
        if (empty($email)) {
            return;
        }

        if (!$uniqueEmailConstraint instanceof UniqueEmail) {
            throw new UnexpectedTypeException($uniqueEmailConstraint, UniqueEmail::class);
        }

        $merchantTransfer = $this->findMerchantByEmail($email);

        if ($merchantTransfer === null) {
            return;
        }

        if ($uniqueEmailConstraint->getCurrentIdMerchant() === $merchantTransfer->getIdMerchant()) {
            return;
        }

        $this->context->buildViolation(static::ERROR_MESSAGE_PROVIDED_EMAIL_IS_ALREADY_USED)
            ->atPath('merchant_email')
            ->addViolation();
    }

    /**
     * @param string $email
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    protected function findMerchantByEmail(string $email): ?MerchantTransfer
    {
        $merchantCriteriaFilterTransfer = new MerchantCriteriaFilterTransfer();
        $merchantCriteriaFilterTransfer->setEmail($email);

        return $this->getFactory()->getMerchantFacade()->findOne($merchantCriteriaFilterTransfer);
    }
}
