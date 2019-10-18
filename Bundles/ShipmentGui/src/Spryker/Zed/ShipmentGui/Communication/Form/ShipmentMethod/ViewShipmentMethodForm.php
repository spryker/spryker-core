<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\ShipmentMethod;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ShipmentGui\Communication\Provider\ViewShipmentMethodFormDataProvider;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShipmentGui\ShipmentGuiConfig getConfig()
 */
class ViewShipmentMethodForm extends AbstractType
{
    public const FIELD_STORE_RELATION = 'storeRelation';
    public const FIELD_PRICES = 'prices';
    public const FIELD_TAX_SET = 'fkTaxSet';
    public const OPTION_AMOUNT_PER_STORE = 'amount_per_store';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'shipment_method';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ShipmentMethodTransfer::class,
        ]);
        $resolver->setRequired([
            ViewShipmentMethodFormDataProvider::OPTION_TAX_SET_CHOICES,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addStoreRelationForm($builder)
            ->addPricesField($builder)
            ->addTaxSetField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addStoreRelationForm(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_STORE_RELATION,
            $this->getFactory()->getStoreRelationFormTypePlugin()->getType(),
            [
                'label' => false,
                'required' => false,
                'disabled' => true,
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPricesField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_PRICES,
            $this->getFactory()->getMoneyCollectionFormTypePlugin()->getType(),
            [
                static::OPTION_AMOUNT_PER_STORE => true,
                'disabled' => true,
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
    protected function addTaxSetField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_TAX_SET,
            ChoiceType::class,
            [
                'label' => 'Tax set',
                'choices' => array_flip($options[ViewShipmentMethodFormDataProvider::OPTION_TAX_SET_CHOICES]),
                'required' => false,
                'disabled' => true,
            ]
        );

        return $this;
    }
}
