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
    public function validate($value, Constraint $constraint)
    {
        if (!$value->getUrl()) {
            return;
        }

        if (!$constraint instanceof UniqueUrl) {
            throw new UnexpectedTypeException($constraint, UniqueUrl::class);
        }

        $url = $this->buildUrl($value, $constraint);

        if (!$this->isUrlChanged($url, $value, $constraint)) {
            return;
        }

        if ($this->hasUrl($url, $constraint, $value->getIdCmsPage())) {
            $this->context
                ->buildViolation(sprintf('Provided Url "%s" is already taken.', $url))
                ->atPath('url')
                ->addViolation();
        }
    }

    /**
     * @param string $url
     * @param \Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueUrl $constraint
     * @param int|null $idCmsPage
     *
     * @return bool
     */
    protected function hasUrl($url, UniqueUrl $constraint, $idCmsPage = null)
    {
        $urlTransfer = new UrlTransfer();
        $urlTransfer->setFkResourcePage($idCmsPage);
        $urlTransfer->setUrl($url);

        return $constraint->getUrlFacade()->hasUrl($urlTransfer);
    }

    /**
     * @param string $url
     * @param \Generated\Shared\Transfer\CmsPageAttributesTransfer $submittedPageAttributesTransfer
     * @param \Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueUrl $constraint
     *
     * @return bool
     */
    protected function isUrlChanged(
        $url,
        CmsPageAttributesTransfer $submittedPageAttributesTransfer,
        UniqueUrl $constraint
    ) {
        $urlTransfer = $this->findUrl($constraint, $url);

        if ($urlTransfer === null) {
            return true;
        }

        if ($urlTransfer->getFkResourcePage() && (int)$urlTransfer->getFkResourcePage() === (int)$submittedPageAttributesTransfer->getIdCmsPage()) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageAttributesTransfer $submittedPageAttributesTransfer
     * @param \Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueUrl $constraint
     *
     * @return bool
     */
    protected function buildUrl(CmsPageAttributesTransfer $submittedPageAttributesTransfer, UniqueUrl $constraint)
    {
        return $constraint->getCmsFacade()->buildPageUrl($submittedPageAttributesTransfer);
    }

    /**
     * @param \Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueUrl $constraint
     * @param string $url
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    protected function findUrl(UniqueUrl $constraint, $url)
    {
        $urlTransfer = new UrlTransfer();
        $urlTransfer->setUrl($url);

        $urlTransfer = $constraint->getUrlFacade()
            ->findUrl($urlTransfer);

        return $urlTransfer;
    }
}
