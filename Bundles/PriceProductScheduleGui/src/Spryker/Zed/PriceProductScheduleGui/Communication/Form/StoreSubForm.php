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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig getConfig()
 */
class StoreSubForm extends AbstractType
{
    public const FIELD_ID_STORE = 'idStore';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIdStore($builder);
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event): void {
            /** @var \Generated\Shared\Transfer\StoreTransfer $storeTransfer */
            $storeTransfer = $event->getData();
            $currencyChoices = array_flip(
                $this->getFactory()
                    ->createPriceProductScheduleFormDataProvider()
                    ->getOptions($storeTransfer->getIdStore())[PriceProductScheduleFormDataProvider::OPTION_CURRENCY_CHOICES]
            );

            $event->getForm()
                ->getParent()
                ->get(MoneyValueSubForm::FIELD_CURRENCY)
                ->add(CurrencySubForm::FIELD_ID_CURRENCY, ChoiceType::class, [
                    'label' => 'Currency',
                    'placeholder' => 'Choose currency',
                    'choices' => $currencyChoices,
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]);
        });
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdStore(FormBuilderInterface $builder)
    {
        $storeValues = array_flip(
            $this->getFactory()
                ->createPriceProductScheduleFormDataProvider()
            ->getOptions()[PriceProductScheduleFormDataProvider::OPTION_STORE_CHOICES]
        );

        $builder->add(static::FIELD_ID_STORE, ChoiceType::class, [
            'label' => 'Store',
            'choices' => $storeValues,
            'placeholder' => 'Choose store',
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }
}
