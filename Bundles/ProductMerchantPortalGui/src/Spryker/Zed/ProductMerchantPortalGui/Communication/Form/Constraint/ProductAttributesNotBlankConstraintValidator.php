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

class ProductAttributesNotBlankConstraintValidator extends ConstraintValidator
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
     * @param \Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\ProductAttributesNotBlank|\Symfony\Component\Validator\Constraint $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if ($value === null || $value === '') {
            return;
        }

        /** @var \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ProductAttributesNotBlankConstraint $constraint */
        $this->validateAtLeastOneAttributeValueNotBlank($value, $constraint);
    }

    /**
     * @param string[][] $attributes
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ProductAttributesNotBlankConstraint $constraint
     *
     * @return void
     */
    protected function validateAtLeastOneAttributeValueNotBlank(
        array $attributes,
        ProductAttributesNotBlankConstraint $constraint
    ): void {
        $parentFormData = $this->getParentFormData();

        $idProductAbstract = $parentFormData[ProductAbstractTransfer::ID_PRODUCT_ABSTRACT];

        $existingAttributes = $constraint->getProductAttributeFacade()
            ->getProductAbstractAttributeValues($idProductAbstract);

        $existingLocaleNames = $this->getLocaleNames($existingAttributes);

        foreach ($attributes as $attributesRowNumber => $formAttribute) {
            if ($this->isAllAttributesEmpty($formAttribute, $existingLocaleNames)) {
                $this->context->buildViolation($constraint->getMessage())
                    ->setParameter('attributesRowNumber', $attributesRowNumber)
                    ->addViolation();
            }
        }
    }

    /**
     * @param string[] $formAttribute
     * @param string[] $existingLocaleNames
     *
     * @return bool
     */
    private function isAllAttributesEmpty(array $formAttribute, array $existingLocaleNames): bool
    {
        foreach ($existingLocaleNames as $localeName) {
            if (!empty($formAttribute[$localeName])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string[][] $existingAttributes
     *
     * @return string[]
     */
    protected function getLocaleNames(array $existingAttributes): array
    {
        $result = [];

        foreach (array_keys($existingAttributes) as $localeName) {
            $result[] = (string)(static::LOCALE_NAME_MAP[$localeName] ?? $localeName);
        }

        return $result;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function getParentFormData(): ProductAbstractTransfer
    {
        /** @var \Symfony\Component\Form\Form $form */
        $form = $this->context->getObject();
        /** @var \Symfony\Component\Form\Form $parentForm */
        $parentForm = $form->getParent();

        return $parentForm->getData();
    }
}
