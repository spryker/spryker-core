<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolumeGui\Communication\Form;

use Generated\Shared\Transfer\PriceProductVolumeItemTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
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
    protected const FIELD_ID_PRODUCT_ABSTRACT = 'idProductAbstract';
    protected const FIELD_ID_PRODUCT_CONCRETE = 'idProductConcrete';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addVolumesField($builder)
            ->addIdStoreField($builder)
            ->addIdCurrencyField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addVolumesField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_VOLUMES, CollectionType::class, [
            'entry_type' => PriceVolumeFormType::class,
            'entry_options' => [
                'label' => 'Collection',
                'data_class' => PriceProductVolumeItemTransfer::class,
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
}
