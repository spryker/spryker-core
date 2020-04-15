<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication\Form\Constraint;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Spryker\Zed\MerchantProfileGuiPage\Communication\Form\MerchantProfileUrlCollection\MerchantProfileUrlCollectionFormType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @method \Spryker\Zed\MerchantProfileGuiPage\Communication\MerchantProfileGuiPageCommunicationFactory getFactory()
 */
class UniqueUrlValidator extends AbstractConstraintValidator
{
    /**
     * Checks if the passed url is unique.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $value The value that should be validated
     * @param \Symfony\Component\Validator\Constraint $constraint The constraint for the validation
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value->getUrl()) {
            return;
        }

        if (!$constraint instanceof UniqueUrl) {
            throw new UnexpectedTypeException($constraint, UniqueUrl::class);
        }

        if (!$this->isUrlChanged($value, $constraint)) {
            return;
        }

        if ($this->hasUrlCaseInsensitive($value->getUrl())) {
            $this->context
                ->buildViolation(sprintf('Provided URL "%s" is already taken.', $value->getUrl()))
                ->atPath(MerchantProfileUrlCollectionFormType::FIELD_URL)
                ->addViolation();
        }
    }

    /**
     * @param string $url
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    protected function findExistingUrl(string $url): ?UrlTransfer
    {
        $urlTransfer = $this->createUrlTransfer($url);

        return $this->getFactory()->getUrlFacade()->findUrlCaseInsensitive($urlTransfer);
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    protected function hasUrlCaseInsensitive(string $url): bool
    {
        $urlTransfer = $this->createUrlTransfer($url);

        return $this->getFactory()->getUrlFacade()->hasUrlCaseInsensitive($urlTransfer);
    }

    /**
     * @param string $url
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function createUrlTransfer(string $url): UrlTransfer
    {
        $urlTransfer = new UrlTransfer();
        $urlTransfer->setUrl($url);

        return $urlTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     * @param \Spryker\Zed\MerchantProfileGuiPage\Communication\Form\Constraint\UniqueUrl $constraint
     *
     * @return bool
     */
    protected function isUrlChanged(UrlTransfer $urlTransfer, UniqueUrl $constraint): bool
    {
        $existingUrlTransfer = $this->findExistingUrl($urlTransfer->getUrl());

        if (!$existingUrlTransfer) {
            return true;
        }

        $merchantProfileId = $existingUrlTransfer->getFkResourceMerchantProfile();

        if (!$merchantProfileId) {
            return true;
        }

        return (int)$merchantProfileId !== (int)$urlTransfer->getFkResourceMerchantProfile();
    }
}
