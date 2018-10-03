<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Form\Products;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\ProductSetGui\Communication\ProductSetGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface getQueryContainer()
 */
class ProductsFormType extends AbstractType
{
    public const FIELD_ASSIGN_ID_PRODUCT_ABSTRACTS = 'assign_id_product_abstracts';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addAssignProductAbstractIdsField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAssignProductAbstractIdsField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ASSIGN_ID_PRODUCT_ABSTRACTS, HiddenType::class, [
            'attr' => [
                'id' => static::FIELD_ASSIGN_ID_PRODUCT_ABSTRACTS,
            ],
            'constraints' => [
                new Callback([
                    'callback' => function (array $productAbstractIds, ExecutionContextInterface $context) {
                        if (count($productAbstractIds) < 2) {
                            $context->addViolation('You need to select minimum 2 products.');
                        }
                    },
                ]),
            ],
        ]);

        $builder->get(static::FIELD_ASSIGN_ID_PRODUCT_ABSTRACTS)
            ->addModelTransformer(new CallbackTransformer(
                function (?array $productAbstractIds = null) {
                    return implode(',', (array)$productAbstractIds);
                },
                function ($productAbstractIds = '') {
                    return $productAbstractIds ? explode(',', $productAbstractIds) : [];
                }
            ));

        return $this;
    }
}
