<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAttributeGuiTableConfigurationProvider;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 */
class ProductAbstractAttributeUniqueCombinationConstraintValidator extends ConstraintValidator
{
    /**
     * @uses \Spryker\Zed\ProductAttribute\ProductAttributeConfig::DEFAULT_LOCALE
     */
    public const DEFAULT_LOCALE = '_';

    public const LOCALE_NAME_MAP = [
        self::DEFAULT_LOCALE => ProductAttributeGuiTableConfigurationProvider::COL_KEY_ATTRIBUTE_DEFAULT,
    ];

    /**
     * @param mixed $value
     * @param \Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\ProductAttributeUniqueCombination|\Symfony\Component\Validator\Constraint $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!(is_array($value) && $constraint instanceof ProductAbstractAttributeUniqueCombinationConstraint)) {
            return;
        }

        $this->validateAttributeUniqueCombination($value, $constraint);
    }

    /**
     * @param string[][] $attributes
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ProductAbstractAttributeUniqueCombinationConstraint $constraint
     *
     * @return void
     */
    protected function validateAttributeUniqueCombination(array $attributes, ProductAbstractAttributeUniqueCombinationConstraint $constraint): void
    {
        $parentFormData = $this->getFormData();

        $idProductAbstract = $parentFormData[ProductAbstractTransfer::ID_PRODUCT_ABSTRACT];

        $existingAttributes = $constraint->getProductAttributeFacade()
            ->getProductAbstractAttributeValues($idProductAbstract);

        foreach ($attributes as $attributesRowNumber => $formAttribute) {
            $attributeName = $formAttribute['attribute_name'] ?? '';

            foreach ($existingAttributes as $localizedAttributes) {
                if (isset($localizedAttributes[$attributeName])) {
                    $this->context->buildViolation($constraint->getMessage($attributeName))
                        ->setParameter('attributesRowNumber', $attributesRowNumber)
                        ->addViolation();

                    break;
                }
            }
        }
    }

    /**
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function getFormData(): ProductAbstractTransfer
    {
        /** @var \Symfony\Component\Form\Form $form */
        $form = $this->context->getObject();
        /** @var \Symfony\Component\Form\Form $parentForm */
        $parentForm = $form->getParent();

        return $parentForm->getData();
    }
}
