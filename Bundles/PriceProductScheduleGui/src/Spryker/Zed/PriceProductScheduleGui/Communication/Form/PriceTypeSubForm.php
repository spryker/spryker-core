<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider\PriceProductScheduleFormDataProvider;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig getConfig()
 */
class PriceTypeSubForm extends AbstractType
{
    public const FIELD_ID_PRICE_TYPE = 'idPriceType';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined([
            PriceProductScheduleFormDataProvider::OPTION_PRICE_TYPE_CHOICES,
            PriceProductScheduleFormDataProvider::OPTION_IS_PRICE_TYPE_DISABLED,
        ]);
        $resolver->setRequired([
            PriceProductScheduleFormDataProvider::OPTION_PRICE_TYPE_CHOICES,
            PriceProductScheduleFormDataProvider::OPTION_IS_PRICE_TYPE_DISABLED,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addFkPriceType($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addFkPriceType(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_ID_PRICE_TYPE, ChoiceType::class, [
            'label' => 'Price type',
            'placeholder' => 'Choose price type',
            'choices' => array_flip($options[PriceProductScheduleFormDataProvider::OPTION_PRICE_TYPE_CHOICES]),
            'constraints' => [
                new NotBlank(),
            ],
            'disabled' => $options[PriceProductScheduleFormDataProvider::OPTION_IS_PRICE_TYPE_DISABLED],
        ]);

        return $this;
    }
}
