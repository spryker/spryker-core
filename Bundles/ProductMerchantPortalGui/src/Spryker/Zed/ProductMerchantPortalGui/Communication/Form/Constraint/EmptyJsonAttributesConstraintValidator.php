<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint;

use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 */
class EmptyJsonAttributesConstraintValidator extends AbstractConstraintValidator
{
    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\AttributesDataProvider::DATA_KEY_ATTRIBUTES
     *
     * @var string
     */
    protected const DATA_KEY_ATTRIBUTES = 'attributes';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\AttributesDataProvider::DATA_KEY_VALUES
     *
     * @var string
     */
    protected const DATA_KEY_VALUE = 'value';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\AddProductConcreteForm::FIELD_EXISTING_ATTRIBUTES
     *
     * @var string
     */
    protected const FIELD_EXISTING_ATTRIBUTES = 'existing_attributes';

    /**
     * @param mixed|string $value Attributes that should be validated
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Form\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof EmptyJsonAttributesConstraint) {
            throw new UnexpectedTypeException($constraint, EmptyJsonAttributesConstraint::class);
        }

        $value = $this->getFactory()->getUtilEncodingService()->decodeJson($value, true);
        $attributesIndexedByValue = is_array($value) ? $this->getAttributesIndexedByValue($value) : [];
        $existingAttributes = $this->getExistingAttributes();

        foreach ($existingAttributes as $index => $attribute) {
            if (
                !isset($attributesIndexedByValue[$attribute[static::DATA_KEY_VALUE]])
                || empty($attributesIndexedByValue[$attribute[static::DATA_KEY_VALUE]])
            ) {
                $this->context
                    ->buildViolation($constraint->getMessage())
                    ->atPath(sprintf('[%s]', (string)$index))
                    ->addViolation();
            }
        }
    }

    /**
     * @param array<mixed> $attributes
     *
     * @return array<string>
     */
    protected function getAttributesIndexedByValue(array $attributes): array
    {
        $indexedAttributes = [];
        foreach ($attributes as $attribute) {
            $indexedAttributes[$attribute[static::DATA_KEY_VALUE]] = $attribute[static::DATA_KEY_ATTRIBUTES];
        }

        return $indexedAttributes;
    }

    /**
     * @return array<mixed>
     */
    protected function getExistingAttributes(): array
    {
        /** @var \Symfony\Component\Form\FormInterface<mixed> $form */
        $form = $this->context->getObject();
        /** @var \Symfony\Component\Form\FormInterface<mixed> $baseForm */
        $baseForm = $form->getParent();
        /** @var \Generated\Shared\Transfer\ProductConcreteTransfer|array<string, mixed> $formData */
        $formData = $baseForm->getData();

        return $this->getFactory()->getUtilEncodingService()->decodeJson(
            $formData[static::FIELD_EXISTING_ATTRIBUTES],
            true,
        ) ?: [];
    }
}
