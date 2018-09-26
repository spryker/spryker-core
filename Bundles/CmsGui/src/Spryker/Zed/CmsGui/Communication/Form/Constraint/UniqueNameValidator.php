<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueNameValidator extends ConstraintValidator
{
    /**
     * Checks if the passed value is valid.
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

        if (!$constraint instanceof UniqueName) {
            throw new UnexpectedTypeException($constraint, UniqueName::class);
        }

        if (!$this->isCmsPageNameModified($value, $constraint)) {
            return;
        }

        if ($this->countCmsPageLocalizedAttributesByName($value->getName(), $constraint) > 0) {
            $this->context
                ->buildViolation(sprintf('Page name "%s" is already taken.', $value->getName()))
                ->atPath('name')
                ->addViolation();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageAttributesTransfer $submittedPageAttributesTransfer
     * @param \Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueName $constraint
     *
     * @return bool
     */
    protected function isCmsPageNameModified(
        CmsPageAttributesTransfer $submittedPageAttributesTransfer,
        UniqueName $constraint
    ) {

        if (!$submittedPageAttributesTransfer->getIdCmsPage()) {
            return true;
        }

        $cmsPageLocalizedAttributesEntity = $this->findCmsPageLocalizedAttributesByNameAndId(
            $submittedPageAttributesTransfer->getName(),
            $submittedPageAttributesTransfer->getIdCmsPage(),
            $constraint
        );

        if ($cmsPageLocalizedAttributesEntity === null) {
            return true;
        }

        if ($submittedPageAttributesTransfer->getName() === $cmsPageLocalizedAttributesEntity->getName()) {
            return false;
        }

        return true;
    }

    /**
     * @param string $name
     * @param \Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueName $constraint
     *
     * @return int
     */
    protected function countCmsPageLocalizedAttributesByName($name, UniqueName $constraint)
    {
        return $constraint->getCmsQueryContainer()
            ->queryCmsPageLocalizedAttributes()
            ->filterByName($name)
            ->count();
    }

    /**
     * @param string $name
     * @param int $idCmsPage
     * @param \Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueName $constraint
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes|null
     */
    protected function findCmsPageLocalizedAttributesByNameAndId($name, $idCmsPage, UniqueName $constraint)
    {
        return $constraint->getCmsQueryContainer()
            ->queryCmsPageLocalizedAttributes()
            ->filterByName($name)
            ->filterByFkCmsPage($idCmsPage)
            ->findOne();
    }
}
