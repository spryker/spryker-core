<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider\PriceProductScheduleFormDataProvider;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig getConfig()
 */
class StoreSubForm extends AbstractType
{
    public const FIELD_ID_STORE = 'idStore';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined([
            PriceProductScheduleFormDataProvider::OPTION_STORE_CHOICES,
        ]);

        $resolver->setRequired([
            PriceProductScheduleFormDataProvider::OPTION_STORE_CHOICES,
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
        $this->addIdStore($builder, $options);
        $builder->addEventListener(FormEvents::POST_SUBMIT, [$this, 'initializeCurrencySubForm']);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'initializeCurrencySubForm']);
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function initializeCurrencySubForm(FormEvent $event): void
    {
        /** @var \Generated\Shared\Transfer\StoreTransfer $storeTransfer */
        $storeTransfer = $event->getData();
        $choices = [];
        if ($storeTransfer !== null) {
            $choices = $this->getCurrencyChoices($storeTransfer);
        }

        $parentForm = $event->getForm()
            ->getParent();

        if ($parentForm === null) {
            return;
        }

        if (!$parentForm->has(MoneyValueSubForm::FIELD_CURRENCY)) {
            return;
        }

        $parentForm
            ->get(MoneyValueSubForm::FIELD_CURRENCY)
            ->add(CurrencySubForm::FIELD_ID_CURRENCY, ChoiceType::class, [
                'label' => 'Currency',
                'placeholder' => 'Choose currency',
                'choices' => $choices,
                'constraints' => [
                    new NotBlank(),
                ],
            ]);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array
     */
    protected function getCurrencyChoices(StoreTransfer $storeTransfer): array
    {
        return array_flip(
            $this->getFactory()
                ->createPriceProductScheduleFormDataProvider()
                ->getOptions(
                    $storeTransfer->getIdStore()
                )[PriceProductScheduleFormDataProvider::OPTION_CURRENCY_CHOICES]
        );
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addIdStore(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_ID_STORE, ChoiceType::class, [
            'label' => 'Store',
            'choices' => array_flip($options[PriceProductScheduleFormDataProvider::OPTION_STORE_CHOICES]),
            'placeholder' => 'Choose store',
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }
}
