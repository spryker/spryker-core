<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\ProductManagement\Communication\Form\Product\ImageSetForm;
use Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\SkuRegex;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ProductFormEdit extends ProductFormAdd
{
    /**
     * @return array
     */
    protected function getValidationGroups()
    {
        $validationGroups = parent::getValidationGroups();

        return array_filter($validationGroups, function ($item) {
            return $item !== ImageSetForm::VALIDATION_GROUP_IMAGE_COLLECTION;
        });
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ProductFormEdit';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSkuField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_SKU, 'text', [
                'label' => 'SKU Prefix',
                'required' => true,
                'read_only' => true,
                'constraints' => [
                    new NotBlank([
                        'groups' => [self::VALIDATION_GROUP_UNIQUE_SKU],
                    ]),
                    new SkuRegex([
                        'groups' => [self::VALIDATION_GROUP_UNIQUE_SKU],
                    ]),
                    new Callback([
                        'methods' => [
                            function ($sku, ExecutionContextInterface $context) {
                                $form = $context->getRoot();
                                $idProductAbstract = $form->get(ProductFormAdd::FIELD_ID_PRODUCT_ABSTRACT)->getData();
                                $sku = $this->utilTextService->generateSlug($sku);

                                $skuCount = $this->productQueryContainer
                                    ->queryProduct()
                                    ->filterByFkProductAbstract($idProductAbstract, Criteria::NOT_EQUAL)
                                    ->filterBySku($sku)
                                    ->_or()
                                    ->useSpyProductAbstractQuery()
                                    ->filterBySku($sku)
                                    ->endUse()
                                    ->count();

                                if ($skuCount > 0) {
                                    $context->addViolation(
                                        sprintf('The SKU "%s" is already used', $sku)
                                    );
                                }
                            },
                        ],
                        'groups' => [self::VALIDATION_GROUP_UNIQUE_SKU],
                    ]),
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addAttributeSuperForm(FormBuilderInterface $builder, array $options = [])
    {
        return $this;
    }
}
