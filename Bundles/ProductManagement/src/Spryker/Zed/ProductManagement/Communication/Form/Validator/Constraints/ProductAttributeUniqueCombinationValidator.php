<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 */
class ProductAttributeUniqueCombinationValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param \Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\SkuUnique|\Symfony\Component\Validator\Constraint $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value === null || $value === '') {
            return;
        }

        $this->validateAttributeUniqueCombination($value, $constraint);
    }

    /**
     * @param mixed $value
     * @param \Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\ProductAttributeUniqueCombination $constraint
     *
     * @return void
     */
    protected function validateAttributeUniqueCombination($value, ProductAttributeUniqueCombination $constraint)
    {
        $attributes = $constraint->getConcreteSuperAttributeFilterHelper()->getTransformedSubmittedSuperAttributes($value);
        $concreteProducts = $constraint->getProductFacade()->getConcreteProductsByAbstractProductId($constraint->getIdProductAbstract());

        if (!$this->validateCombinationExistence($attributes, $this->getAttributeCombinationsFromConcreteProducts($concreteProducts))) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ combination }}', $this->formValidationMessagePlaceholder($attributes))
                ->addViolation();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $concreteProducts
     *
     * @return array
     */
    protected function getAttributeCombinationsFromConcreteProducts(array $concreteProducts)
    {
        return array_map(function (ProductConcreteTransfer $productConcreteTransfer) {
            return $productConcreteTransfer->getAttributes();
        }, $concreteProducts);
    }

    /**
     * @param array $submittedAttributes
     * @param array $existingProductAttributes
     *
     * @return bool
     */
    protected function validateCombinationExistence(array $submittedAttributes, array $existingProductAttributes)
    {
        foreach ($existingProductAttributes as $comnbination) {
            if (!count(array_diff_assoc($submittedAttributes, $comnbination))) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $submittedAttributes
     * @param array $existingProductAttributes
     *
     * @return array
     */
    protected function getExistingProductAttributesFilledWithEmptyAttributes(array $submittedAttributes, array $existingProductAttributes)
    {
        foreach ($submittedAttributes as $submittedAttributeKey => $submittedAttributeValue) {
            $existingProductAttributes[$submittedAttributeKey] = $existingProductAttributes[$submittedAttributeKey] ?? null;
        }

        return $existingProductAttributes;
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    protected function formValidationMessagePlaceholder(array $attributes)
    {
        $placeholder = '';

        foreach ($attributes as $attributeKey => $attributeValue) {
            if ($attributeValue !== null) {
                $placeholder .= '[' . $attributeKey . ']' . ' - ' . $attributeValue . ', ';
            }
        }

        return '"' . rtrim($placeholder, ', ') . '"';
    }
}
