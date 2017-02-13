<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\CmsPageTransfer;
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
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\NameUnique');
        }

        if (!$this->isCmsPageNameModified($value, $constraint)) {
            return;
        }

        if ($this->countCmsPageLocalizedAttributesByName($value, $constraint) > 0) {
            $this->buildViolation('This name is already taken.')
                ->addViolation();
        }
    }

    /**
     * @param string $submitedName
     * @param \Spryker\Zed\CmsGui\Communication\Form\Constraint\UniqueName $constraint
     *
     * @return bool
     */
    protected function isCmsPageNameModified($submitedName, UniqueName $constraint)
    {
        $cmsPageTransfer = $this->getCmsPageTransfer();

        if (!$cmsPageTransfer->getFkPage()) {
            return true;
        }

        $cmsPageAttributesTransfer = $this->findProcessedCmsPageAttributesTransfer($submitedName, $cmsPageTransfer);

        $cmsPageLocalizedAttributesEntity = $this->findCmsPageLocalizedAttributesByNameAndId(
            $submitedName,
            $cmsPageTransfer->getFkPage(),
            $constraint
        );

        if ($cmsPageLocalizedAttributesEntity === null) {
            return true;
        }

        if ($cmsPageAttributesTransfer->getName() === $cmsPageLocalizedAttributesEntity->getName()) {
            return false;
        }

        return true;
    }

    /**
     * @param string $submitedName
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return \Generated\Shared\Transfer\CmsPageAttributesTransfer|null
     */
    protected function findProcessedCmsPageAttributesTransfer($submitedName, CmsPageTransfer $cmsPageTransfer)
    {
        $submitedPageAttributesTransfer = null;
        foreach ($cmsPageTransfer->getPageAttributes() as $pageAttributesTransfer) {
            if ($pageAttributesTransfer->getName() === $submitedName) {
                $submitedPageAttributesTransfer = $pageAttributesTransfer;
            }
        }
        return $submitedPageAttributesTransfer;
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
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes
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
