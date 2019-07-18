<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\ProductManagement\Communication\Form\Product\ImageSetForm;
use Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints\SkuRegex;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 */
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
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSkuField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_SKU, TextType::class, [
                'label' => 'SKU Prefix',
                'required' => true,
                'attr' => [
                    'readonly' => 'readonly',
                ],
                'constraints' => [
                    new NotBlank([
                        'groups' => [self::VALIDATION_GROUP_UNIQUE_SKU],
                    ]),
                    new SkuRegex([
                        'groups' => [self::VALIDATION_GROUP_UNIQUE_SKU],
                    ]),
                    new Callback([
                        'callback' => function ($sku, ExecutionContextInterface $context) {
                            $form = $context->getRoot();
                            $idProductAbstract = $form->get(ProductFormAdd::FIELD_ID_PRODUCT_ABSTRACT)->getData();

                            $skuCount = $this->getFactory()->getProductQueryContainer()
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
