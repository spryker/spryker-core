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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\Store\Business\StoreFacadeInterface getFacade()
 * @method \Spryker\Zed\Store\Communication\StoreCommunicationFactory getFactory()
 * @method \Spryker\Zed\Store\StoreConfig getConfig()
 * @method \Spryker\Zed\Store\Persistence\StoreQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Store\Persistence\StoreRepositoryInterface getRepository()
 */
class StoreRelationToggleType extends AbstractType
{
    public const FIELD_ID_ENTITY = 'id_entity';
    public const FIELD_ID_STORES = 'id_stores';
    public const FIELD_ID_STORES_DISABLED = 'id_stores_disabled';

    public const STORE_TOGGLE_NAME = 'Store relation';

    public const MESSAGE_MULTI_STORE_PER_ZED_DISABLED = 'Multi-store per Zed feature is disabled';

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

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $this->setInitialData($event);
            }
        );
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
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    protected function setInitialData(FormEvent $event)
    {
        $dataProvider = $this->getFactory()->createStoreRelationToggleDataProvider();

        if ($event->getData()) {
            return;
        }

        $event->setData($dataProvider->getDefaultFormData());
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
        if ($this->getConfig()->isMultiStorePerZedEnabled()) {
            return $this->addFieldEditableIdStores($builder);
        }

        return $this->addFieldImmutableIdStores($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFieldEditableIdStores(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_ID_STORES,
            ChoiceType::class,
            [
                'label' => static::STORE_TOGGLE_NAME,
                'expanded' => true,
                'multiple' => true,
                'choices' => array_flip($this->getStoreNameMap()),
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFieldImmutableIdStores(FormBuilderInterface $builder)
    {
        $storeToggleName = sprintf('%s (%s)', static::STORE_TOGGLE_NAME, static::MESSAGE_MULTI_STORE_PER_ZED_DISABLED);

        $builder->add(
            static::FIELD_ID_STORES_DISABLED,
            ChoiceType::class,
            [
                'label' => $storeToggleName,
                'expanded' => true,
                'disabled' => true,
                'property_path' => static::FIELD_ID_STORES,
                'multiple' => true,
                'choices' => array_flip($this->getStoreNameMap()),
            ]
        );

        $builder->add(static::FIELD_ID_STORES, HiddenType::class);
        $builder->get(static::FIELD_ID_STORES)->addModelTransformer(
            $this->getFactory()->createIdStoresDataTransformer()
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
