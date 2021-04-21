<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\MerchantGui\Communication\Form\MerchantUrlCollection\MerchantUrlCollectionFormType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueUrlValidator extends ConstraintValidator
{
    /**
     * Checks if the passed url is unique.
     *
     * @api
     *
     * @param mixed $value The value that should be validated
     * @param \Symfony\Component\Validator\Constraint $constraint The constraint for the validation
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        /** @var \Generated\Shared\Transfer\UrlTransfer $value */
        if (!$value->getUrl()) {
            return;
        }
        if (!$constraint instanceof UniqueUrl) {
            throw new UnexpectedTypeException($constraint, UniqueUrl::class);
        }

        if ($this->hasUrl($value, $constraint)) {
            $this->context
                ->buildViolation(sprintf('Provided URL "%s" is already taken.', $value->getUrl()))
                ->atPath(MerchantUrlCollectionFormType::FIELD_URL)
                ->addViolation();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     * @param \Spryker\Zed\MerchantGui\Communication\Form\Constraint\UniqueUrl $constraint
     *
     * @return bool
     */
    protected function hasUrl(UrlTransfer $urlTransfer, UniqueUrl $constraint): bool
    {
        $existingUrlTransfer = $constraint->getUrlFacade()->findUrlCaseInsensitive(
            (new UrlTransfer())->setUrl($urlTransfer->getUrl())
        );

        if (
            !$existingUrlTransfer ||
            $existingUrlTransfer->getFkResourceMerchant() &&
            (int)$existingUrlTransfer->getFkResourceMerchant() === (int)$urlTransfer->getFkResourceMerchant()
        ) {
            return false;
        }

        return true;
    }
}
