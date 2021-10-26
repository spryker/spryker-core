<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
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
     *
     * @var string
     */
    public const DEFAULT_LOCALE = '_';

    /**
     * @var array
     */
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
     * @param array<string[]> $attributes
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ProductAbstractAttributeUniqueCombinationConstraint $constraint
     *
     * @return void
     */
    protected function validateAttributeUniqueCombination(array $attributes, ProductAbstractAttributeUniqueCombinationConstraint $constraint): void
    {
        $existingAttributes = [];
        $parentFormData = $this->getParentFormData();

        if ($parentFormData instanceof ProductAbstractTransfer) {
            $idProductAbstract = $parentFormData[ProductAbstractTransfer::ID_PRODUCT_ABSTRACT];

            $existingAttributes = $constraint->getProductAttributeFacade()->getProductAbstractAttributeValues($idProductAbstract);
        }

        if ($parentFormData instanceof ProductConcreteTransfer) {
            $idProductConcrete = $parentFormData[ProductConcreteTransfer::ID_PRODUCT_CONCRETE];
            $productConcreteTransfer = $constraint->getProductFacade()->findProductConcreteById($idProductConcrete);

            if ($productConcreteTransfer) {
                $attributesGroupedByLocaleName = $this->getAttributesGroupedByLocaleName(
                    $productConcreteTransfer->getLocalizedAttributes(),
                );
                $existingAttributes = $this->appendAttributesWithDefaultAttributes(
                    $attributesGroupedByLocaleName,
                    $productConcreteTransfer->getAttributes(),
                );
            }
        }

        foreach ($attributes as $attributesRowNumber => $formAttribute) {
            $attributeName = $formAttribute['attribute_name'] ?? '';

            foreach ($existingAttributes as $attributes) {
                if (isset($attributes[$attributeName])) {
                    $this->context->buildViolation($constraint->getMessage($attributeName))
                        ->setParameter('attributesRowNumber', $attributesRowNumber)
                        ->addViolation();

                    break;
                }
            }
        }
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function getParentFormData(): AbstractTransfer
    {
        /** @var \Symfony\Component\Form\Form $form */
        $form = $this->context->getObject();
        /** @var \Symfony\Component\Form\Form $parentForm */
        $parentForm = $form->getParent();

        return $parentForm->getData();
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributesTransfers
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributesTransfers
     *
     * @return array<string[]>
     */
    protected function getAttributesGroupedByLocaleName(ArrayObject $localizedAttributesTransfers): array
    {
        $result = [];

        foreach ($localizedAttributesTransfers as $localizedAttributeTransfer) {
            $localeName = $localizedAttributeTransfer->getLocaleOrFail()->getLocaleName();
            $result[$localeName] = $localizedAttributeTransfer->getAttributes();
        }

        return $result;
    }

    /**
     * @param array<string[]> $attributes
     * @param array<string> $defaultAttributes
     *
     * @return array<string[]>
     */
    protected function appendAttributesWithDefaultAttributes(array $attributes, array $defaultAttributes): array
    {
        $attributes[static::DEFAULT_LOCALE] = $defaultAttributes;

        ksort($attributes);

        return $attributes;
    }
}
