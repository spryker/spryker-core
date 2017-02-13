<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\CmsPageTransfer;
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
        if (!$value) {
            return;
        }

        if (!$constraint instanceof UniqueUrl) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\UniqueUrl');
        }

        $url = $this->buildUrl($value, $constraint);

        if (!$this->isUrlChanged($url, $constraint)) {
            return;
        }

        if (!$this->isUniqueUrl($url, $constraint)) {
            $this->buildViolation('Url is already taken.')
                ->addViolation();
        }
    }

    /**
     * @param string $url
     * @param \Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueUrl $constraint
     *
     * @return bool
     */
    protected function isUniqueUrl($url, UniqueUrl $constraint)
    {
        return $constraint->getUrlFacade()->hasUrl($url) === false;
    }

    /**
     * @param string $url
     * @param \Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueUrl $constraint
     *
     * @return bool
     */
    protected function isUrlChanged($url, UniqueUrl $constraint)
    {
        $urlTransfer = $this->findUrl($constraint, $url);

        if ($urlTransfer === null) {
            return true;
        }

        $cmsPageTransfer = $this->getCmsPageTransfer();
        if ((int)$urlTransfer->getFkResourcePage() === (int)$cmsPageTransfer->getFkPage()) {
            return false;
        }

        return true;
    }

    /**
     * @param string $submitedUrl
     * @param \Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueUrl $constraint
     *
     * @return bool
     */
    protected function buildUrl($submitedUrl, UniqueUrl $constraint)
    {
        $cmsPageTransfer = $this->getCmsPageTransfer();
        $submitedPageAttributesTransfer = $this->findProcessedCmsPageAttributesTransfer($submitedUrl, $cmsPageTransfer);
        if ($submitedPageAttributesTransfer === null) {
            return true;
        }

        return $constraint->getCmsFacade()->buildPageUrl($submitedPageAttributesTransfer);
    }

    /**
     * @param string $submitedUrl
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return \Generated\Shared\Transfer\CmsPageAttributesTransfer|null
     */
    protected function findProcessedCmsPageAttributesTransfer($submitedUrl, CmsPageTransfer $cmsPageTransfer)
    {
        $submitedPageAttributesTransfer = null;
        foreach ($cmsPageTransfer->getPageAttributes() as $pageAttributesTransfer) {
            if ($pageAttributesTransfer->getUrl() === $submitedUrl) {
                $submitedPageAttributesTransfer = $pageAttributesTransfer;
            }
        }
        return $submitedPageAttributesTransfer;
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

    /**
     * @return \Symfony\Component\Form\Form
     */
    protected function getRootForm()
    {
        return $this->context->getRoot();
    }

    /**
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    protected function getCmsPageTransfer()
    {
        $root = $this->getRootForm();

        return $root->getData();
    }

}
