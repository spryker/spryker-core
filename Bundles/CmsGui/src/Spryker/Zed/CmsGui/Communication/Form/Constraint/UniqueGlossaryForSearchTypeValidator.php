<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\CmsGlossaryAttributesTransfer;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueGlossaryForSearchTypeValidator extends ConstraintValidator
{

    /**
     * Checks if the passed translationKey is valid.
     *
     * @param mixed $value The translationKey that should be validated
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

        if (!$constraint instanceof UniqueGlossaryForSearchType) {
            throw new UnexpectedTypeException($constraint, UniqueGlossaryForSearchType::class);
        }

        $cmsGlossaryTransfer = $this->getCmsGlossaryTransfer();
        $cmsAttributesGlossaryTransfer = $this->findCmsGlossaryAttributesTransfer($cmsGlossaryTransfer, $value);

        if ($cmsAttributesGlossaryTransfer->getSearchOption() != $constraint->getGlossarySearchTypeValidate()) {
            return;
        }

        if (!$this->isKeyChanged($value, $constraint, $cmsAttributesGlossaryTransfer)) {
            return;
        }

        $hasKey = $constraint->getGlossaryFacade()
            ->hasKey($value);

        if ($hasKey) {
            $this->context
                ->buildViolation(sprintf('Translation key "%s" is already taken.', $value))
                ->addViolation();
        }
    }

    /**
     * @param string $value
     * @param \Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueGlossaryForSearchType $constraint
     * @param \Generated\Shared\Transfer\CmsGlossaryAttributesTransfer $cmsGlossaryAttributesTransfer
     *
     * @return bool
     */
    protected function isKeyChanged(
        $value,
        UniqueGlossaryForSearchType $constraint,
        CmsGlossaryAttributesTransfer $cmsGlossaryAttributesTransfer
    ) {
        $hasKey = $constraint->getGlossaryFacade()
            ->hasKey($value);

        if (!$hasKey) {
            return false;
        }

        $idGlossaryKey = $constraint->getGlossaryFacade()
            ->getKeyIdentifier($value);

        if ($idGlossaryKey === $cmsGlossaryAttributesTransfer->getFkGlossaryKey()) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     * @param string $translationKey
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryAttributesTransfer|null
     */
    protected function findCmsGlossaryAttributesTransfer(CmsGlossaryTransfer $cmsGlossaryTransfer, $translationKey)
    {
        foreach ($cmsGlossaryTransfer->getGlossaryAttributes() as $cmsGlossaryAttributesTransfer) {
            if ($cmsGlossaryAttributesTransfer->getTranslationKey() === $translationKey) {
                return $cmsGlossaryAttributesTransfer;
            }
        }

        return null;
    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    protected function getRootForm()
    {
        return $this->context->getRoot();
    }

    /**
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    protected function getCmsGlossaryTransfer()
    {
        $root = $this->getRootForm();

        return $root->getData();
    }

}
