<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Form\Constraint;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueOptionValueSkuValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     *
     * @api
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueOptionValueSku) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\UniqueOptionValueSku');
        }

        if (!$this->isSkuChanged($value, $constraint)) {
            return;
        }

        if (!$this->isUniqueSku($value, $constraint)) {
            $this->buildViolation('Product option with this sku is already used.')
                ->addViolation();
        }
    }

    /**
     * @param string $sku
     * @param UniqueOptionValueSku $constraint
     *
     * @return bool
     */
    protected function isUniqueSku($sku, UniqueOptionValueSku $constraint)
    {
        $numberOfDiscounts = $constraint->getProductOptionQueryContainer()
            ->queryProductOptionValueBySku($sku)
            ->count();

        return $numberOfDiscounts === 0;
    }

    /**
     * @param string $submittedSku
     * @param UniqueOptionValueSku $constraint
     *
     * @return bool
     */
    protected function isSkuChanged($submittedSku, UniqueOptionValueSku $constraint)
    {
        /* @var $root Form */
        $root = $this->context->getRoot();

        $idProductOptionValue = $this->findProductOptionValueId($root->getData(), $submittedSku);
        if (!$idProductOptionValue) {
            return true;
        }

        $discountEntity = $constraint->getProductOptionQueryContainer()
            ->queryProductOptionByValueId($idProductOptionValue)
            ->findOne();

        if ($discountEntity->getSku() !== $submittedSku) {
            return true;
        }

        return false;

    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     * @param string $submittedSku
     *
     * @return int
     */
    protected function findProductOptionValueId(ProductOptionGroupTransfer $productOptionGroupTransfer, $submittedSku)
    {
        foreach ($productOptionGroupTransfer->getProductOptionValues() as $productOptionValueTransfer) {
            if ($productOptionValueTransfer->getSku() === $submittedSku) {
                return $productOptionValueTransfer->getIdProductOptionValue();
            }
        }
    }

}
