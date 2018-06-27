<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Spryker\Zed\ProductPackagingUnitGui\Communication\Form\ProductPackagingUnitTypeFormType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueProductPackagingUnitTypeNameConstraintValidator extends ConstraintValidator
{
    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $value
     * @param \Spryker\Zed\ProductPackagingUnitGui\Communication\Form\Constraint\UniqueProductPackagingUnitTypeNameConstraint $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        $this->assertConstraintType($constraint);
        $this->assertValueType($value);

        if (!$this->isNameChanged($value, $constraint)) {
            return;
        }

        if ($this->isUniqueName($value, $constraint)) {
            return;
        }

        $this
            ->context
            ->buildViolation($constraint->getMessage($value->getName()))
            ->atPath(ProductPackagingUnitTypeFormType::FIELD_NAME)
            ->addViolation();
    }

    /**
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    protected function assertConstraintType(Constraint $constraint)
    {
        if (!($constraint instanceof UniqueProductPackagingUnitTypeNameConstraint)) {
            throw new UnexpectedTypeException($constraint, UniqueProductPackagingUnitTypeNameConstraint::class);
        }
    }

    /**
     * @param mixed $value
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    protected function assertValueType($value)
    {
        if (!($value instanceof ProductPackagingUnitTypeTransfer)) {
            throw new UnexpectedTypeException($value, ProductPackagingUnitTypeTransfer::class);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     * @param \Spryker\Zed\ProductPackagingUnitGui\Communication\Form\Constraint\UniqueProductPackagingUnitTypeNameConstraint $constraint
     *
     * @return bool
     */
    protected function isNameChanged(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer,
        Constraint $constraint
    ) {
        if (!$productPackagingUnitTypeTransfer->getIdProductPackagingUnitType()) {
            return true;
        }

        $productPackagingUnitTypeTransfer = $constraint->findProductPackagingUnitTypeById($productPackagingUnitTypeTransfer);

        if (!$productPackagingUnitTypeTransfer) {
            return true;
        }

        return $productPackagingUnitTypeTransfer->getName() !== $productPackagingUnitTypeTransfer->getName();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     * @param \Spryker\Zed\ProductPackagingUnitGui\Communication\Form\Constraint\UniqueProductPackagingUnitTypeNameConstraint $constraint
     *
     * @return bool
     */
    protected function isUniqueName(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer,
        Constraint $constraint
    ) {
        $productPackagingUnitTypeTransfer = $constraint->findProductPackagingUnitTypeByName($productPackagingUnitTypeTransfer);

        return $productPackagingUnitTypeTransfer ? false : true;
    }
}
