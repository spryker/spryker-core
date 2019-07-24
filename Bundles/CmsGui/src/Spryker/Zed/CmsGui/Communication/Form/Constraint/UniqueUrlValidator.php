<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueUrlValidator extends ConstraintValidator
{
    protected const ERROR_MESSAGE_PROVIDED_URL_IS_ALREADY_TAKEN = 'Provided URL "%s" is already taken.';

    /**
     * Checks if the passed url is unique.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsPageAttributesTransfer|mixed $cmsPageAttributesTransfer The value that should be validated
     * @param \Symfony\Component\Validator\Constraint $uniqueUrlConstraint The constraint for the validation
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($cmsPageAttributesTransfer, Constraint $uniqueUrlConstraint)
    {
        if (!$cmsPageAttributesTransfer->getUrl()) {
            return;
        }

        if (!$uniqueUrlConstraint instanceof UniqueUrl) {
            throw new UnexpectedTypeException($uniqueUrlConstraint, UniqueUrl::class);
        }

        $submittedUrlTransfer = $this->buildUrlTransfer($cmsPageAttributesTransfer, $uniqueUrlConstraint);
        $existingUrlTransfer = $uniqueUrlConstraint->getUrlFacade()->findUrlCaseInsensitive($submittedUrlTransfer);

        if ($existingUrlTransfer === null || $existingUrlTransfer->getFkResourcePage() === null) {
            return;
        }

        if ($existingUrlTransfer->getFkResourcePage() === $submittedUrlTransfer->getFkResourcePage()) {
            return;
        }

        if ($existingUrlTransfer->getUrl() !== $submittedUrlTransfer->getUrl()) {
            return;
        }

        $this->context->buildViolation(sprintf(static::ERROR_MESSAGE_PROVIDED_URL_IS_ALREADY_TAKEN, $submittedUrlTransfer->getUrl()))
            ->atPath('url')
            ->addViolation();
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageAttributesTransfer $cmsPageAttributesTransfer
     * @param \Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueUrl $uniqueUrlConstraint
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function buildUrlTransfer(CmsPageAttributesTransfer $cmsPageAttributesTransfer, UniqueUrl $uniqueUrlConstraint): UrlTransfer
    {
        $url = $uniqueUrlConstraint->getCmsFacade()->buildPageUrl($cmsPageAttributesTransfer);

        return (new UrlTransfer())
            ->setUrl($url)
            ->setFkResourcePage($cmsPageAttributesTransfer->getIdCmsPage());
    }
}
