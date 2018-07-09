<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\ProductLabelGui\Communication\Form\ProductLabelFormType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueProductLabelNameConstraintValidator extends ConstraintValidator
{
    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $value
     * @param \Spryker\Zed\ProductLabelGui\Communication\Form\Constraint\UniqueProductLabelNameConstraint $constraint
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
            ->atPath(ProductLabelFormType::FIELD_NAME)
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
        if (!($constraint instanceof UniqueProductLabelNameConstraint)) {
            throw new UnexpectedTypeException($constraint, UniqueProductLabelNameConstraint::class);
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
        if (!($value instanceof ProductLabelTransfer)) {
            throw new UnexpectedTypeException($value, ProductLabelTransfer::class);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     * @param \Spryker\Zed\ProductLabelGui\Communication\Form\Constraint\UniqueProductLabelNameConstraint $constraint
     *
     * @return bool
     */
    protected function isNameChanged(
        ProductLabelTransfer $productLabelTransfer,
        UniqueProductLabelNameConstraint $constraint
    ) {
        $idProductLabel = $productLabelTransfer->getIdProductLabel();
        if (!$idProductLabel) {
            return true;
        }

        $productLabelEntity = $constraint->findProductLabelById($idProductLabel);

        return ($productLabelEntity->getName() !== $productLabelTransfer->getName());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     * @param \Spryker\Zed\ProductLabelGui\Communication\Form\Constraint\UniqueProductLabelNameConstraint $constraint
     *
     * @return bool
     */
    protected function isUniqueName(
        ProductLabelTransfer $productLabelTransfer,
        UniqueProductLabelNameConstraint $constraint
    ) {
        $productLabelEntity = $constraint->findProductLabelByName($productLabelTransfer->getName());

        return ($productLabelEntity ? false : true);
    }
}
