<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueUrlValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
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
        if (!$constraint instanceof UniqueUrl) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\UniqueUrl');
        }

        if (!$this->isUrlChanged($constraint)) {
            return;
        }

        if (!$this->isUniqueUrl($value, $constraint)) {
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
     * @param \Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueUrl $constraint
     *
     * @return bool
     */
    protected function isUrlChanged(UniqueUrl $constraint)
    {
        /** @var \Symfony\Component\Form\Form $root */
        $root = $this->context->getRoot();

        /** @var \Generated\Shared\Transfer\CmsPageTransfer $data */
        $cmsPageTransfer = $root->getData();

        $idCmsPageTransfer = $cmsPageTransfer->getFkPage();

        if (!$idCmsPageTransfer) {
            return true;
        }

        //$cmsPageUrl = $constraint->getCmsFacade()->buildPageUrl($cmsPageTransfer);

    }

}
