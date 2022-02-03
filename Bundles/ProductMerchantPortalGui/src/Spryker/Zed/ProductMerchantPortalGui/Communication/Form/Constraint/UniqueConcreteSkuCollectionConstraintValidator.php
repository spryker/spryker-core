<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueConcreteSkuCollectionConstraintValidator extends AbstractConstraintValidator
{
    /**
     * @param array<mixed> $value Concrete products that should be validated
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueConcreteSkuCollectionConstraint) {
            throw new UnexpectedTypeException($constraint, UniqueConcreteSkuCollectionConstraint::class);
        }

        if (!is_array($value)) {
            throw new UnexpectedTypeException($value, 'array');
        }

        $skuCounts = [];

        foreach ($value as $concreteProduct) {
            $sku = $concreteProduct[ProductConcreteTransfer::SKU];

            if (!isset($skuCounts[$sku])) {
                $skuCounts[$sku] = 0;
            }

            $skuCounts[$sku] = ++$skuCounts[$sku];
        }

        foreach ($value as $index => $concreteProduct) {
            $sku = $concreteProduct[ProductConcreteTransfer::SKU];

            if ($skuCounts[$sku] > 1) {
                $this->context
                    ->buildViolation($constraint->getMessage())
                    ->atPath(sprintf('[%s][%s]', (string)$index, ProductConcreteTransfer::SKU))
                    ->addViolation();
            }
        }
    }
}
