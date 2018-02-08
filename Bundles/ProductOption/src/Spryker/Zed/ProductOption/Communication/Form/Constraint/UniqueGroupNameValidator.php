<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Form\Constraint;

use Spryker\Zed\ProductOption\ProductOptionConfig;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueGroupNameValidator extends ConstraintValidator
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
        if (!$constraint instanceof UniqueGroupName) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\UniqueGroupName');
        }

        if (!$this->hasTranslationPrefix($value)) {
            $value = $this->addTranslationPrefix($value);
        }

        if (!$this->isGroupNameChanged($value, $constraint)) {
            return;
        }

        if (!$this->isUniqueGroupName($value, $constraint)) {
            $this->context->buildViolation(
                sprintf(
                    'Group with "%s" translation key is already created.',
                    $value
                )
            )->addViolation();
        }
    }

    /**
     * @param string $groupName
     * @param \Spryker\Zed\ProductOption\Communication\Form\Constraint\UniqueGroupName $constraint
     *
     * @return bool
     */
    protected function isUniqueGroupName($groupName, UniqueGroupName $constraint)
    {
        $numberOfDiscounts = $constraint->getProductOptionQueryContainer()
            ->queryProductOptionGroupByName($groupName)
            ->count();

        return $numberOfDiscounts === 0;
    }

    /**
     * @param string $submittedGroupName
     * @param \Spryker\Zed\ProductOption\Communication\Form\Constraint\UniqueGroupName $constraint
     *
     * @return bool
     */
    protected function isGroupNameChanged($submittedGroupName, UniqueGroupName $constraint)
    {
        /** @var \Symfony\Component\Form\Form $root */
        $root = $this->context->getRoot();

        /** @var \Generated\Shared\Transfer\ProductOptionGroupTransfer $data */
        $data = $root->getData();
        $idProductOptionGroup = $data->getIdProductOptionGroup();

        if (!$idProductOptionGroup) {
            return true;
        }

        $productOptionGroupEntity = $constraint->getProductOptionQueryContainer()
            ->queryProductOptionGroupById($idProductOptionGroup)
            ->findOne();

        if ($productOptionGroupEntity->getName() !== $submittedGroupName) {
            return true;
        }

        return false;
    }

    /**
     * @param string $groupName
     *
     * @return string
     */
    protected function addTranslationPrefix($groupName)
    {
        return ProductOptionConfig::PRODUCT_OPTION_GROUP_NAME_TRANSLATION_PREFIX . $groupName;
    }

    /**
     * @param string $groupName
     *
     * @return bool
     */
    protected function hasTranslationPrefix($groupName)
    {
        return strpos($groupName, ProductOptionConfig::PRODUCT_OPTION_GROUP_NAME_TRANSLATION_PREFIX) === 0;
    }
}
