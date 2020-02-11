<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints;

use ArrayObject;
use Spryker\Zed\ProductManagement\Communication\Form\Product\Price\ProductMoneyCollectionType;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ProductPriceNotBlankValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\ProductPriceNotBlank|\Symfony\Component\Validator\Constraint $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value === null || $value === '') {
            return;
        }

        /** @var \Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\ProductPriceNotBlank $constraint */
        $this->validateProductPriceNotBlank($value, $constraint);
    }

    /**
     * @param mixed $value
     * @param \Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\ProductPriceNotBlank $constraint
     *
     * @return void
     */
    protected function validateProductPriceNotBlank($value, ProductPriceNotBlank $constraint)
    {
        $formData = $this->context->getRoot()->getData();

        if ($formData[ProductFormAdd::FORM_PRICE_DIMENSION]) {
            return;
        }

        foreach ($this->getGrouppedPricesArray($value) as $priceGroup) {
            if ($this->validatePriceGroup($priceGroup)) {
                return;
            }
        }

        $this->context->buildViolation($constraint->message)->addViolation();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceGroup
     *
     * @return bool
     */
    protected function validatePriceGroup(array $priceGroup)
    {
        foreach ($priceGroup as $priceProductTransfer) {
            $moneyValueTransfer = $priceProductTransfer->getMoneyValue();

            if ($moneyValueTransfer->getGrossAmount() !== null || $moneyValueTransfer->getNetAmount() !== null) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $productPrices
     *
     * @return array
     */
    protected function getGrouppedPricesArray(ArrayObject $productPrices)
    {
        $grouppedPrices = [];

        foreach ($productPrices as $compositeKey => $priceProductTransfer) {
            $grouppedPrices[$this->getGroupKeyFromCompositePriceKey($compositeKey)][] = $priceProductTransfer;
        }

        return $grouppedPrices;
    }

    /**
     * @param string $compositeKey
     *
     * @return string
     */
    protected function getGroupKeyFromCompositePriceKey(string $compositeKey)
    {
        $keyPartials = explode(ProductMoneyCollectionType::PRICE_DELIMITER, $compositeKey);

        return $keyPartials[0] . ProductMoneyCollectionType::PRICE_DELIMITER . $keyPartials[1];
    }
}
