<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication\Form\Type;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\Store\Business\StoreFacadeInterface getFacade()
 */
class StoreRelationToggleType extends AbstractType
{
    const FIELD_ID_ENTITY = 'id_entity';
    const FIELD_ID_STORES = 'id_stores';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addFieldIdEntity($builder)
            ->addFieldIdStores($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => StoreRelationTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFieldIdEntity(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_ENTITY, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFieldIdStores(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_ID_STORES,
            ChoiceType::class,
            [
                'label' => 'Store relation',
                'expanded' => true,
                'multiple' => true,
                'choices' => $this->getStoreNameMap(),
            ]
        );

        return $this;
    }

    /**
     * @return string[] Keys are store ids, values are store names.
     */
    protected function getStoreNameMap()
    {
        $storeTransferCollection = $this->getFacade()->getAllStores();

        $storeNameMap = [];
        foreach ($storeTransferCollection as $storeTransfer) {
            $storeNameMap[$storeTransfer->getIdStore()] = $storeTransfer->getName();
        }

        return $storeNameMap;
    }
}
