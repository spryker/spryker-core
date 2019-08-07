<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig getConfig()
 */
class PriceTypeSubForm extends AbstractType
{
    public const FIELD_ID_PRICE_TYPE = 'idPriceType';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addFkPriceType($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkPriceType(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_PRICE_TYPE, ChoiceType::class, [
            'label' => 'Price type',
            'placeholder' => 'Choose price type',
            'choices' => array_flip($this->getFactory()->createPriceProductScheduleFormDataProvider()->getPriceTypeValues()),
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }
}
