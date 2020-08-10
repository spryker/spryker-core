<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\ProductRelationTransfer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ProductAbstractNotBlankConstraintValidator extends ConstraintValidator
{
    protected const VIOLATION_MESSAGE = 'Abstract product is not selected.';

    /**
     * @param mixed|\Generated\Shared\Transfer\ProductRelationTransfer $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value) {
            return;
        }

        if (!$constraint instanceof ProductAbstractNotBlankConstraint) {
            throw new UnexpectedTypeException($constraint, ProductAbstractNotBlankConstraint::class);
        }

        if (!$this->isIdProductAbstractExists($value->getFkProductAbstract())) {
            $this->createViolationMessage();
        }
    }

    /**
     * @param int|null $idProductAbstract
     *
     * @return bool
     */
    protected function isIdProductAbstractExists(?int $idProductAbstract): bool
    {
        return $idProductAbstract !== null;
    }

    /**
     * @return void
     */
    protected function createViolationMessage(): void
    {
        $this->context
            ->buildViolation(static::VIOLATION_MESSAGE)
            ->atPath(ProductRelationTransfer::FK_PRODUCT_ABSTRACT)
            ->addViolation();
    }
}
