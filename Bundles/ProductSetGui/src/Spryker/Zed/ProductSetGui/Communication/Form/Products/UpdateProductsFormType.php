<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Form\Products;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UpdateProductsFormType extends AbstractType
{

    const FIELD_ASSIGN_ID_PRODUCT_ABSTRACTS = 'assign_id_product_abstracts';
    const FIELD_DEASSIGN_ID_PRODUCT_ABSTRACTS = 'deassign_id_product_abstracts';
    const FIELD_ID_PRODUCT_ABSTRACTS = 'id_product_abstracts';
    const FIELD_PRODUCT_ORDER = 'product_order';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addAssignProductAbstractIdsField($builder)
            ->addDeassignProductAbstractIdsField($builder)
            ->addProductAbstractIdsField($builder)
            ->addProductOrderField($builder);

        $builder->addEventListener(
            FormEvents::SUBMIT,
            [$this, 'onSubmit']
        );
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAssignProductAbstractIdsField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ASSIGN_ID_PRODUCT_ABSTRACTS, HiddenType::class, [
            'attr' => [
                'id' => self::FIELD_ASSIGN_ID_PRODUCT_ABSTRACTS,
            ],
        ]);

        // TODO: reuse
        $builder->get(self::FIELD_ASSIGN_ID_PRODUCT_ABSTRACTS)
            ->addModelTransformer(new CallbackTransformer(
                function (array $productAbstractIds = null) {
                    return implode(',', (array)$productAbstractIds);
                },
                function ($productAbstractIds = '') {
                    return $productAbstractIds ? explode(',', $productAbstractIds) : [];
                }
            ));

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDeassignProductAbstractIdsField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_DEASSIGN_ID_PRODUCT_ABSTRACTS, HiddenType::class, [
            'attr' => [
                'id' => self::FIELD_DEASSIGN_ID_PRODUCT_ABSTRACTS,
            ],
        ]);

        $builder->get(self::FIELD_DEASSIGN_ID_PRODUCT_ABSTRACTS)
            ->addModelTransformer(new CallbackTransformer(
                function (array $productAbstractIds = null) {
                    return implode(',', (array)$productAbstractIds);
                },
                function ($productAbstractIds = '') {
                    return $productAbstractIds ? explode(',', $productAbstractIds) : [];
                }
            ));

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductAbstractIdsField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ID_PRODUCT_ABSTRACTS, HiddenType::class, [
//            'constraints' => [
//                new Callback([
//                    'methods' => [
//                        function (array $productAbstractIds, ExecutionContextInterface $context) {
//                            if (count($productAbstractIds) < 2) {
//                                $context->addViolation('You need to select minimum 2 products.');
//                            }
//                        },
//                    ]
//                ]),
//            ],
        ]);

        $builder->get(self::FIELD_ID_PRODUCT_ABSTRACTS)
            ->addModelTransformer(new CallbackTransformer(
                function (array $productAbstractIds = null) {
                    return implode(',', (array)$productAbstractIds);
                },
                function ($productAbstractIds = '') {
                    return $productAbstractIds ? explode(',', $productAbstractIds) : [];
                }
            ));

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductOrderField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_PRODUCT_ORDER, HiddenType::class, [
            'attr' => [
                'id' => 'product_order',
            ],
        ]);

        $builder->get(self::FIELD_PRODUCT_ORDER)
            ->addModelTransformer(new CallbackTransformer(
                function ($productAbstractIds = null) {
                    return json_encode((array)$productAbstractIds); // FIXME
                },
                function ($productAbstractIds = '{}') {
                    return json_decode($productAbstractIds, true);
                }
            ));

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function onSubmit(FormEvent $event)
    {
        $data = $event->getData();

        // sort by given order
        $productOrder = $data[self::FIELD_PRODUCT_ORDER];
        asort($productOrder);
        $idProductAbstracts = array_keys($productOrder);

        // add assigned products
        $idProductAbstracts = array_unique(array_merge($idProductAbstracts, $data[self::FIELD_ASSIGN_ID_PRODUCT_ABSTRACTS]));

        // remove deassigned products
        $idProductAbstracts = array_diff($idProductAbstracts, $data[self::FIELD_DEASSIGN_ID_PRODUCT_ABSTRACTS]);

        // reindex product positions
        $idProductAbstracts = array_values($idProductAbstracts);

        $data[self::FIELD_ID_PRODUCT_ABSTRACTS] = $idProductAbstracts;
        $event->setData($data);
    }

}
