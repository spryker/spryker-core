<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\ProductCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 */
class UniqueSkuInProductConcreteCollectionConstraintValidator extends AbstractConstraintValidator
{
    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\AddProductConcreteForm::FIELD_PRODUCTS
     * @var string
     */
    protected const ADD_PRODUCT_CONCRETE_FORM_FIELD_PRODUCTS = 'products';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteSuperAttributeForm::FIELD_SKU
     * @var string
     */
    protected const PRODUCT_CONCRETE_SUPER_ATTRIBUTE_FORM_FIELD_SKU = 'sku';

    /**
     * @var string
     */
    protected const PROPERTY_PATH_TEMPLATE = '[products][%d][%s]';

    /**
     * Checks if SKUs are unique in collection and do not exist in DB.
     *
     * @param mixed[] $value
     * @param \Symfony\Component\Validator\Constraint|\Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\UniqueSkuInProductConcreteCollectionConstraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueSkuInProductConcreteCollectionConstraint) {
            throw new UnexpectedTypeException($constraint, UniqueSkuInProductConcreteCollectionConstraint::class);
        }

        $skus = [];
        $skuIndexes = [];
        foreach ($value[static::ADD_PRODUCT_CONCRETE_FORM_FIELD_PRODUCTS] as $index => $productConcreteSuperAttributeFormData) {
            $sku = $productConcreteSuperAttributeFormData[static::PRODUCT_CONCRETE_SUPER_ATTRIBUTE_FORM_FIELD_SKU];

            if (isset($skus[$sku])) {
                $this->context
                    ->buildViolation($constraint->getMessageValueUnique())
                    ->atPath(sprintf(
                        static::PROPERTY_PATH_TEMPLATE,
                        (int)$index,
                        static::PRODUCT_CONCRETE_SUPER_ATTRIBUTE_FORM_FIELD_SKU
                    ))
                    ->addViolation();
            }

            $skus[$sku] = $sku;
            $skuIndexes[$sku] = $index;
        }

        $productConcreteTransfers = $this->getFactory()->getProductFacade()->getProductConcretesByCriteria(
            (new ProductCriteriaTransfer())->setSkus(array_values($skus))
        );

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            if (!isset($skuIndexes[$productConcreteTransfer->getSku()])) {
                continue;
            }

            $this->context
                ->buildViolation($constraint->getMessageValueExists())
                ->atPath(sprintf(
                    static::PROPERTY_PATH_TEMPLATE,
                    (int)$skuIndexes[$productConcreteTransfer->getSku()],
                    static::PRODUCT_CONCRETE_SUPER_ATTRIBUTE_FORM_FIELD_SKU
                ))
                ->addViolation();
        }
    }
}
