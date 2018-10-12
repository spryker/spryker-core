<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolumeGui\Communication\Form;

use Generated\Shared\Transfer\PriceProductVolumeItemTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\PriceProductVolumeGui\Communication\Form\DataProvider\PriceVolumeCollectionDataProvider;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\PriceProductVolumeGui\Business\PriceProductVolumeGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductVolumeGui\Communication\PriceProductVolumeGuiCommunicationFactory getFactory()
 */
class PriceVolumeCollectionFormType extends AbstractType
{
    public const FIELD_VOLUMES = 'volumes';
    public const FIELD_ID_STORE = 'idStore';
    public const FIELD_ID_CURRENCY = 'idCurrency';
    public const FIELD_ID_PRODUCT_ABSTRACT = 'idProductAbstract';
    public const FIELD_ID_PRODUCT_CONCRETE = 'idProductConcrete';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addVolumesField($builder, $options)
            ->addIdStoreField($builder)
            ->addIdCurrencyField($builder)
            ->addIdProductAbstractField($builder)
            ->addIdProductConcreteField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addVolumesField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_VOLUMES, CollectionType::class, [
            'entry_type' => PriceVolumeFormType::class,
            'entry_options' => [
                'label' => false,
                'data_class' => PriceProductVolumeItemTransfer::class,
                PriceVolumeCollectionDataProvider::OPTION_CURRENCY_CODE => $options[PriceVolumeCollectionDataProvider::OPTION_CURRENCY_CODE],
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdStoreField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_STORE, HiddenType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Id store not defined.']),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCurrencyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_CURRENCY, HiddenType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Id currency not defined.']),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductAbstractField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_PRODUCT_ABSTRACT, HiddenType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Id product abstract not defined.']),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductConcreteField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_PRODUCT_CONCRETE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(PriceVolumeCollectionDataProvider::OPTION_CURRENCY_CODE);
    }
}
