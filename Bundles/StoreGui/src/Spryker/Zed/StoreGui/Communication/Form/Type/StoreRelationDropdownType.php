<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGui\Communication\Form\Type;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\StoreGui\StoreGuiConfig getConfig()
 * @method \Spryker\Zed\StoreGui\Communication\StoreGuiCommunicationFactory getFactory()
 */
class StoreRelationDropdownType extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_INACTIVE_CHOICES = 'inactive_choices';

    /**
     * @var string
     */
    public const OPTION_DATA_CLASS = 'data_class';

    /**
     * @var string
     */
    public const OPTION_STORE_CHOICES = 'store_choices';

    /**
     * @var string
     */
    public const OPTION_ATTRIBUTE_ACTION_URL = 'action_url';

    /**
     * @var string
     */
    public const OPTION_ATTRIBUTE_ACTION_EVENT = 'action_event';

    /**
     * @var string
     */
    public const OPTION_ATTRIBUTE_ACTION_FIELD = 'action_field';

    /**
     * @var string
     */
    public const OPTION_EXTENDED = 'extended';

    /**
     * @var string
     */
    protected const FIELD_ID_ENTITY = 'id_entity';

    /**
     * @var string
     */
    protected const FIELD_ID_STORES = 'id_stores';

    /**
     * @var string
     */
    protected const FIELD_ID_STORES_DISABLED = 'id_stores_disabled';

    /**
     * @var string
     */
    protected const LABEL_STORES = 'Stores';

    /**
     * @var string
     */
    protected const MESSAGE_MULTI_STORE_PER_ZED_DISABLED = 'Stores (Multi-store per Zed feature is disabled)';

    /**
     * @var string
     */
    protected const BLOCK_PREFIX = 'relation_dropdown';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string> $options
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
        $options = $this->getFactory()
            ->createStoreRelationDropdownDataProvider()
            ->getOptions();

        $resolver->setDefaults($options);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::BLOCK_PREFIX;
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

        $event->setData($dataProvider->getData());
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
        if ($this->getFactory()->getStoreFacade()->isMultiStorePerZedEnabled()) {
            $this->addFieldEditableIdStores($builder, $options);

            return $this;
        }

        $this->addFieldImmutableIdStores($builder, $options);

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
                'choices' => array_flip($options[static::OPTION_STORE_CHOICES]),
                'attr' => [
                    static::OPTION_ATTRIBUTE_ACTION_URL => $options[static::OPTION_ATTRIBUTE_ACTION_URL],
                    static::OPTION_ATTRIBUTE_ACTION_EVENT => $options[static::OPTION_ATTRIBUTE_ACTION_EVENT],
                    static::OPTION_ATTRIBUTE_ACTION_FIELD => $options[static::OPTION_ATTRIBUTE_ACTION_FIELD],
                    static::OPTION_EXTENDED => $options[static::OPTION_EXTENDED],
                ],
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
     * @param array $options
     *
     * @return $this
     */
    protected function addFieldImmutableIdStores(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_ID_STORES_DISABLED,
            Select2ComboBoxType::class,
            [
                'label' => static::MESSAGE_MULTI_STORE_PER_ZED_DISABLED,
                'disabled' => true,
                'property_path' => static::FIELD_ID_STORES,
                'multiple' => true,
                'choices' => array_flip($options[static::OPTION_STORE_CHOICES]),
                'attr' => [
                    static::OPTION_EXTENDED => $options[static::OPTION_EXTENDED],
                ],
            ]
        );

        $builder->add(static::FIELD_ID_STORES, HiddenType::class);
        $builder->get(static::FIELD_ID_STORES)->addModelTransformer(
            $this->getFactory()->createIdStoresDataTransformer()
        );

        return $this;
    }

    /**
     * @param string $idStore
     * @param array $options
     *
     * @return bool
     */
    protected function getIsStoreDisabled(string $idStore, array $options): bool
    {
        $inactiveChoices = $options[static::OPTION_INACTIVE_CHOICES];

        return ($inactiveChoices !== [] && in_array($idStore, $inactiveChoices));
    }
}
