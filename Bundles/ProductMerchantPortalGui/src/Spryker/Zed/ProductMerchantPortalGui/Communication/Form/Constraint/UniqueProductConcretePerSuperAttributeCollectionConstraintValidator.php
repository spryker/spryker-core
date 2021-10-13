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
class UniqueProductConcretePerSuperAttributeCollectionConstraintValidator extends AbstractConstraintValidator
{
    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteSuperAttributeForm::FIELD_SUPER_ATTRIBUTES
     * @var string
     */
    protected const FIELD_SUPER_ATTRIBUTES = 'superAttributes';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\SuperAttributeForm::FIELD_VALUE
     * @var string
     */
    protected const FIELD_VALUE = 'value';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\SuperAttributeForm::FIELD_ATTRIBUTE
     * @var string
     */
    protected const FIELD_ATTRIBUTE = 'attribute';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductAttributeValueForm::FIELD_VALUE
     * @var string
     */
    protected const FIELD_ATTRIBUTE_VALUE = 'value';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\AddProductConcreteForm::FIELD_ID_PRODUCT_ABSTRACT
     * @var string
     */
    protected const FIELD_ID_PRODUCT_ABSTRACT = 'idProductAbstract';

    /**
     * Checks if combination of super attribute values doesn't exist in DB.
     *
     * @param array<mixed> $value
     * @param \Symfony\Component\Validator\Constraint|\Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\UniqueProductConcretePerSuperAttributeCollectionConstraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueProductConcretePerSuperAttributeCollectionConstraint) {
            throw new UnexpectedTypeException($constraint, UniqueProductConcretePerSuperAttributeCollectionConstraint::class);
        }

        $idProductAbstract = $this->getIdProductAbstract();

        $productCriteriaTransfer = (new ProductCriteriaTransfer())->setIdProductAbstract($idProductAbstract);
        foreach ($value[static::FIELD_SUPER_ATTRIBUTES] as $superAttributeFormData) {
            $productCriteriaTransfer->addAttribute(
                $superAttributeFormData[static::FIELD_VALUE],
                $superAttributeFormData[static::FIELD_ATTRIBUTE][static::FIELD_ATTRIBUTE_VALUE]
            );
        }

        $productConcreteTransfers = $this->getFactory()
            ->getProductFacade()
            ->getProductConcretesByCriteria($productCriteriaTransfer);

        if (count($productConcreteTransfers)) {
            $this->context->addViolation($constraint->getMessage());
        }
    }

    /**
     * @return int
     */
    protected function getIdProductAbstract(): int
    {
        /** @var \Symfony\Component\Form\FormInterface $form */
        $form = $this->context->getObject();
        /** @var \Symfony\Component\Form\FormInterface $parentForm */
        $parentForm = $form->getParent();
        /** @var \Symfony\Component\Form\FormInterface $baseForm */
        $baseForm = $parentForm->getParent();
        /** @var \Generated\Shared\Transfer\ProductConcreteTransfer|array $formData */
        $formData = $baseForm->getData();

        return (int)$formData[static::FIELD_ID_PRODUCT_ABSTRACT];
    }
}
