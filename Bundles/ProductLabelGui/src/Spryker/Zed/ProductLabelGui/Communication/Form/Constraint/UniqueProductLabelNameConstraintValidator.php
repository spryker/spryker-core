<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Form\Constraint;

use Spryker\Zed\ProductLabelGui\Communication\Form\ProductLabelFormType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueProductLabelNameConstraintValidator extends ConstraintValidator
{

    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint|\Spryker\Zed\ProductLabelGui\Communication\Form\Constraint\UniqueProductLabelNameConstraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!($constraint instanceof UniqueProductLabelNameConstraint)) {
            throw new UnexpectedTypeException($constraint, UniqueProductLabelNameConstraint::class);
        }

        if (!$this->isNameChanged($value, $constraint)) {
            return;
        }

        if ($this->isUniqueName($value, $constraint)) {
            return;
        }

        $this
            ->context
            ->buildViolation($constraint->getMessage($value))
            ->atPath(ProductLabelFormType::FIELD_NAME)
            ->addViolation();
    }

    /**
     * @param string $value
     * @param \Spryker\Zed\ProductLabelGui\Communication\Form\Constraint\UniqueProductLabelNameConstraint $constraint
     *
     * @return bool
     */
    protected function isNameChanged($value, UniqueProductLabelNameConstraint $constraint)
    {
        $idProductLabel = $this->getProductLabelIdFromContext();
        if (!$idProductLabel) {
            return true;
        }

        $productLabelEntity = $constraint->findProductLabelById($idProductLabel);

        return ($productLabelEntity->getName() !== $value);
    }

    /**
     * @return int|null
     */
    protected function getProductLabelIdFromContext()
    {
        /** @var \Symfony\Component\Form\FormInterface $root */
        $root = $this->context->getRoot();

        /** @var \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer */
        $productLabelTransfer = $root->getData();

        return $productLabelTransfer->getIdProductLabel();
    }

    /**
     * @param string $name
     * @param \Spryker\Zed\ProductLabelGui\Communication\Form\Constraint\UniqueProductLabelNameConstraint $constraint
     *
     * @return bool
     */
    protected function isUniqueName($name, UniqueProductLabelNameConstraint $constraint)
    {
        $productLabelEntity = $constraint->findProductLabelByName($name);

        return ($productLabelEntity ? false : true);
    }

}
