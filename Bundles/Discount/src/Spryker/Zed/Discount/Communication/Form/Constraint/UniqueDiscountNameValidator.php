<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueDiscountNameValidator extends ConstraintValidator
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
        if (!$constraint instanceof UniqueDiscountName) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\UniqueDiscountName');
        }

        if (!$this->isNameChanged($value, $constraint)) {
            return;
        }

        if (!$this->isUniqueDiscountName($value, $constraint)) {
            $this->context
                ->buildViolation('Discount with this name is already used.')
                ->addViolation();
        }
    }

    /**
     * @param string $discountName
     * @param \Spryker\Zed\Discount\Communication\Form\Constraint\UniqueDiscountName $constraint
     *
     * @return bool
     */
    protected function isUniqueDiscountName($discountName, UniqueDiscountName $constraint)
    {
        $numberOfDiscounts = $constraint->getDiscountQueryContainer()
            ->queryDiscountName($discountName)
            ->count();

        return $numberOfDiscounts === 0;
    }

    /**
     * @param string $submittedDiscountName
     * @param \Spryker\Zed\Discount\Communication\Form\Constraint\UniqueDiscountName $constraint
     *
     * @return bool
     */
    protected function isNameChanged($submittedDiscountName, UniqueDiscountName $constraint)
    {
        /** @var \Symfony\Component\Form\Form $root */
        $root = $this->context->getRoot();

        /** @var \Generated\Shared\Transfer\DiscountConfiguratorTransfer $data */
        $data = $root->getData();
        $discountGeneralTransfer = $data->getDiscountGeneral();
        $idDiscount = $discountGeneralTransfer->getIdDiscount();

        if (!$idDiscount) {
            return true;
        }

        $discountEntity = $constraint->getDiscountQueryContainer()
            ->queryDiscount()
            ->findOneByIdDiscount($idDiscount);

        if ($discountEntity->getDisplayName() !== $submittedDiscountName) {
            return true;
        }

        return false;
    }

}
