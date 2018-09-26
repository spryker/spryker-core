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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @method \Spryker\Zed\ProductSetGui\Communication\ProductSetGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface getQueryContainer()
 */
class UpdateProductsFormType extends AbstractType
{
    public const FIELD_ASSIGN_ID_PRODUCT_ABSTRACTS = 'assign_id_product_abstracts';
    public const FIELD_DEASSIGN_ID_PRODUCT_ABSTRACTS = 'deassign_id_product_abstracts';
    public const FIELD_ID_PRODUCT_ABSTRACTS = 'id_product_abstracts';
    public const FIELD_PRODUCT_POSITION = 'product_position';

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
            ->addProductPositionField($builder);

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
        $builder->add(static::FIELD_ASSIGN_ID_PRODUCT_ABSTRACTS, HiddenType::class, [
            'attr' => [
                'id' => static::FIELD_ASSIGN_ID_PRODUCT_ABSTRACTS,
            ],
        ]);

        $builder->get(static::FIELD_ASSIGN_ID_PRODUCT_ABSTRACTS)
            ->addModelTransformer($this->createProductAbstractIdsFieldTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDeassignProductAbstractIdsField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_DEASSIGN_ID_PRODUCT_ABSTRACTS, HiddenType::class, [
            'attr' => [
                'id' => static::FIELD_DEASSIGN_ID_PRODUCT_ABSTRACTS,
            ],
        ]);

        $builder->get(static::FIELD_DEASSIGN_ID_PRODUCT_ABSTRACTS)
            ->addModelTransformer($this->createProductAbstractIdsFieldTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductAbstractIdsField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_PRODUCT_ABSTRACTS, HiddenType::class);

        $builder->get(static::FIELD_ID_PRODUCT_ABSTRACTS)
            ->addModelTransformer($this->createProductAbstractIdsFieldTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductPositionField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PRODUCT_POSITION, HiddenType::class, [
            'attr' => [
                'id' => 'product_position',
            ],
        ]);

        $builder->get(static::FIELD_PRODUCT_POSITION)
            ->addModelTransformer(new CallbackTransformer(
                function ($productAbstractIds = null) {
                    return $this->getFactory()->getUtilEncodingService()->encodeJson((array)$productAbstractIds);
                },
                function ($productAbstractIds = '{}') {
                    return $this->getFactory()->getUtilEncodingService()->decodeJson($productAbstractIds, true);
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

        $idProductAbstracts = $this->getSortedIdProductAbstracts($data);
        $idProductAbstracts = $this->addAssignedProducts($idProductAbstracts, $data);
        $idProductAbstracts = $this->removeDeassignedProducts($idProductAbstracts, $data);
        $idProductAbstracts = $this->reindexProductPositions($idProductAbstracts);

        $data[static::FIELD_ID_PRODUCT_ABSTRACTS] = $idProductAbstracts;
        $event->setData($data);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function getSortedIdProductAbstracts(array $data)
    {
        $productPosition = $data[static::FIELD_PRODUCT_POSITION];
        asort($productPosition);

        return array_keys($productPosition);
    }

    /**
     * @param array $idProductAbstracts
     * @param array $data
     *
     * @return array
     */
    protected function addAssignedProducts(array $idProductAbstracts, array $data)
    {
        return array_unique(array_merge($idProductAbstracts, $data[static::FIELD_ASSIGN_ID_PRODUCT_ABSTRACTS]));
    }

    /**
     * @param array $idProductAbstracts
     * @param array $data
     *
     * @return array
     */
    protected function removeDeassignedProducts(array $idProductAbstracts, array $data)
    {
        return array_diff($idProductAbstracts, $data[static::FIELD_DEASSIGN_ID_PRODUCT_ABSTRACTS]);
    }

    /**
     * @param array $idProductAbstracts
     *
     * @return array
     */
    protected function reindexProductPositions(array $idProductAbstracts)
    {
        return array_values($idProductAbstracts);
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createProductAbstractIdsFieldTransformer()
    {
        return new CallbackTransformer(
            function (?array $productAbstractIds = null) {
                return implode(',', (array)$productAbstractIds);
            },
            function ($productAbstractIds = '') {
                return $productAbstractIds ? explode(',', $productAbstractIds) : [];
            }
        );
    }
}
