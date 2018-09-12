<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Form\Constraint;

use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueOptionValueSkuValidator extends ConstraintValidator
{
    /**
     * @var array
     */
    protected $validatedSkus = [];

    /**
     * Checks if the passed value is valid.
     *
     * @param \Generated\Shared\Transfer\ProductOptionValueTransfer $productOptionValueTransfer $productOptionValueTransfer
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($productOptionValueTransfer, Constraint $constraint)
    {
        if (in_array($productOptionValueTransfer->getSku(), $this->validatedSkus)) {
            $this->addUniqueViolationMessage();
        }

        if (!$constraint instanceof UniqueOptionValueSku) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\UniqueOptionValueSku');
        }

        if (!$this->isSkuChanged($productOptionValueTransfer->getSku(), $constraint, $productOptionValueTransfer->getIdProductOptionValue())) {
            return;
        }

        if (!$this->isUniqueSku($productOptionValueTransfer->getSku(), $constraint)) {
            $this->addUniqueViolationMessage();
        }

        $this->validatedSkus[] = $productOptionValueTransfer->getSku();
    }

    /**
     * @return void
     */
    protected function addUniqueViolationMessage()
    {
        $this->context
            ->buildViolation('Product option with this sku is already used.')
            ->atPath(ProductOptionValueTransfer::SKU)
            ->addViolation();
    }

    /**
     * @param string $sku
     * @param \Spryker\Zed\ProductOption\Communication\Form\Constraint\UniqueOptionValueSku $constraint
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
     * @param \Spryker\Zed\ProductOption\Communication\Form\Constraint\UniqueOptionValueSku $constraint
     * @param int $idProductOptionValue
     *
     * @return bool
     */
    protected function isSkuChanged($submittedSku, UniqueOptionValueSku $constraint, $idProductOptionValue)
    {
        if (!$idProductOptionValue) {
            return true;
        }

        $productOptionValueEntity = $constraint->getProductOptionQueryContainer()
            ->queryProductOptionByValueId($idProductOptionValue)
            ->findOne();

        if ($productOptionValueEntity->getSku() !== $submittedSku) {
            return true;
        }

        return false;
    }
}
