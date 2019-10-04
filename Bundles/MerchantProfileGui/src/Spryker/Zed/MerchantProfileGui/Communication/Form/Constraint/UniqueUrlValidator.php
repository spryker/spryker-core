<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\MerchantProfileGui\Communication\Form\MerchantProfileUrlCollection\MerchantProfileUrlCollectionFormType;
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
        if (!$this->isUrlChanged($value, $constraint)) {
            return;
        }
        if ($this->hasUrl($value->getUrl(), $constraint)) {
            $this->context
                ->buildViolation(sprintf('Provided URL "%s" is already taken.', $value->getUrl()))
                ->atPath(MerchantProfileUrlCollectionFormType::FIELD_URL)
                ->addViolation();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     * @param \Spryker\Zed\MerchantProfileGui\Communication\Form\Constraint\UniqueUrl $constraint
     *
     * @return bool
     */
    protected function isUrlChanged(UrlTransfer $urlTransfer, UniqueUrl $constraint): bool
    {
        $existingUrlTransfer = $this->findUrl($urlTransfer->getUrl(), $constraint);
        if ($existingUrlTransfer
            && $existingUrlTransfer->getFkResourceMerchantProfile()
            && (int)$existingUrlTransfer->getFkResourceMerchantProfile() === (int)$urlTransfer->getFkResourceMerchantProfile()
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param string $url
     * @param \Spryker\Zed\MerchantProfileGui\Communication\Form\Constraint\UniqueUrl $constraint
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    protected function findUrl(string $url, UniqueUrl $constraint): ?UrlTransfer
    {
        $urlTransfer = new UrlTransfer();
        $urlTransfer->setUrl($url);
        $urlTransfer = $constraint->getUrlFacade()
            ->findUrlCaseInsensitive($urlTransfer);

        return $urlTransfer;
    }

    /**
     * @param string $url
     * @param \Spryker\Zed\MerchantProfileGui\Communication\Form\Constraint\UniqueUrl $constraint
     *
     * @return bool
     */
    protected function hasUrl(string $url, UniqueUrl $constraint): bool
    {
        $urlTransfer = new UrlTransfer();
        $urlTransfer->setUrl($url);

        return $constraint->getUrlFacade()->hasUrlCaseInsensitive($urlTransfer);
    }
}
