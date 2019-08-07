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
class CurrencySubForm extends AbstractType
{
    public const FIELD_ID_CURRENCY = 'idCurrency';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIdCurrency($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCurrency(FormBuilderInterface $builder)
    {
        $idStore = null;
        $formData = $builder->getData();
        if ($formData !== null) {
            $idStore = $formData->getIdStore();
        }
        $currencyChoices = array_flip(
            $this->getFactory()
                ->createPriceProductScheduleFormDataProvider()
                ->getCurrencyValues($idStore)
        );
        $builder->add(static::FIELD_ID_CURRENCY, ChoiceType::class, [
            'label' => 'Currency',
            'placeholder' => 'Choose currency',
            'choices' => $currencyChoices,
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }
}
