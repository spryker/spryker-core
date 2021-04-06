<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication\Form\Type;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
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
class StoreRelationDrowdownType extends AbstractType
{
    public const FIELD_ID_ENTITY = 'id_entity';
    public const FIELD_ID_STORES = 'id_stores';
    public const FIELD_ID_STORES_DISABLED = 'id_stores_disabled';

    public const OPTION_INACTIVE_CHOICES = 'inactive_choices';
    protected const OPTION_DATA_CLASS = 'data_class';

    protected const LABEL_STORES = 'Stores';

    protected const MESSAGE_MULTI_STORE_PER_ZED_DISABLED = 'Multi-store per Zed feature is disabled';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addFieldIdEntity($builder)
            ->addFieldIdStores($builder, $options);

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
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined([
            static::OPTION_INACTIVE_CHOICES,
        ]);

        $resolver->setDefaults([
            static::OPTION_DATA_CLASS => StoreRelationTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    protected function setInitialData(FormEvent $event): void
    {
        if ($event->getData()) {
            return;
        }

        $dataProvider = $this->getFactory()->createStoreRelationDropdownDataProvider();

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
     * @param array $options
     *
     * @return $this
     */
    protected function addFieldIdStores(FormBuilderInterface $builder, array $options)
    {
        if ($this->getConfig()->isMultiStorePerZedEnabled()) {
            $this->addFieldEditableIdStores($builder, $options);

            return $this;
        }

        $this->addFieldImmutableIdStores($builder);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addFieldEditableIdStores(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_ID_STORES,
            Select2ComboBoxType::class,
            [
                'label' => static::LABEL_STORES,
                'multiple' => true,
                'choices' => array_flip($this->getStoreNameMap()),
                'choice_attr' => function ($idStore) use ($options) {
                    return [
                        'disabled' => $this->getIsStoreDisabled($idStore, $options),
                    ];
                },
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
        $storeToggleName = sprintf('%s (%s)', static::LABEL_STORES, static::MESSAGE_MULTI_STORE_PER_ZED_DISABLED);

        $builder->add(
            static::FIELD_ID_STORES_DISABLED,
            ChoiceType::class,
            [
                'label' => $storeToggleName,
                'disabled' => true,
                'expanded' => true,
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
     * @return string[]
     */
    protected function getStoreNameMap(): array
    {
        $storeTransferCollection = $this->getFacade()->getAllStores();

        $storeNameMap = [];
        foreach ($storeTransferCollection as $storeTransfer) {
            $storeNameMap[$storeTransfer->getIdStore()] = $storeTransfer->getName();
        }

        return $storeNameMap;
    }

    /**
     * @param string $idStore
     * @param array $options
     *
     * @return bool
     */
    protected function getIsStoreDisabled(string $idStore, array $options): bool
    {
        $inactiveChoices = $options[static::OPTION_INACTIVE_CHOICES] ?? [];

        return ($inactiveChoices !== [] && in_array($idStore, $inactiveChoices));
    }
}
