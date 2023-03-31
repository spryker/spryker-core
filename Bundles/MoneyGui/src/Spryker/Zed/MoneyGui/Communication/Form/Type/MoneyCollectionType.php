<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MoneyGui\Communication\Form\Type;

use Countable;
use Generated\Shared\Transfer\MoneyValueCollectionTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractCollectionType;
use Spryker\Zed\MoneyGui\Communication\Form\DataProvider\MoneyCollectionTypeDataProviderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\MoneyGui\Communication\MoneyGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MoneyGui\MoneyGuiConfig getConfig()
 */
class MoneyCollectionType extends AbstractCollectionType
{
    /**
     * @var string
     */
    protected const OPTION_AMOUNT_PER_STORE = 'amount_per_store';

    /**
     * Available if static::OPTION_AMOUNT_PER_STORE value is false.
     *
     * @var string
     */
    protected const OPTION_AMOUNT_PER_CURRENCY = 'amount_per_currency';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $options = $this->overwriteCollectionDefaultEntryType($options);

        $defaultOptions = [
            'entry_options' => [
                'data_class' => MoneyValueTransfer::class,
            ],
        ];

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($options): void {
                $this->setInitialMoneyValueData($event, $options);
            },
        );

        parent::buildForm($builder, array_replace_recursive($defaultOptions, $options));
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            static::OPTION_AMOUNT_PER_STORE => true,
            static::OPTION_AMOUNT_PER_CURRENCY => false,
        ]);

        parent::configureOptions($resolver);
    }

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array<string> $options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        parent::buildView($view, $form, $options);

        $view->vars[static::OPTION_AMOUNT_PER_STORE] = $options[static::OPTION_AMOUNT_PER_STORE];
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'money_collection';
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @param array<string> $options
     *
     * @return void
     */
    protected function setInitialMoneyValueData(FormEvent $event, array $options): void
    {
        $moneyCollectionDataProvider = $this->getFormDataProvider($options);

        if (!$event->getData() instanceof Countable || count($event->getData()) === 0) {
            $event->setData(
                $moneyCollectionDataProvider->getMoneyValuesWithCurrenciesForCurrentStore()->getMoneyValues(),
            );

            return;
        }

        /** @var \ArrayObject<int, \Generated\Shared\Transfer\MoneyValueTransfer> $moneyValueTransfers */
        $moneyValueTransfers = $event->getData();

        $moneyValueCollectionTransfer = (new MoneyValueCollectionTransfer())->setMoneyValues($moneyValueTransfers);
        $moneyValueCollectionTransfer = $moneyCollectionDataProvider->mergeMissingMoneyValues($moneyValueCollectionTransfer);

        $event->setData($moneyValueCollectionTransfer->getMoneyValues());
    }

    /**
     * @param array<string> $options
     *
     * @return \Spryker\Zed\MoneyGui\Communication\Form\DataProvider\MoneyCollectionTypeDataProviderInterface
     */
    protected function getFormDataProvider(array $options): MoneyCollectionTypeDataProviderInterface
    {
        if ($options[static::OPTION_AMOUNT_PER_STORE]) {
            return $this->getFactory()->createMoneyCollectionTypeMultiStoreCollectionDataProvider();
        }

        return (!empty($options[static::OPTION_AMOUNT_PER_CURRENCY]))
            ? $this->getFactory()->createMoneyCollectionTypeAllStoreCurrenciesDataProvider()
            : $this->getFactory()->createMoneyCollectionTypeSingleStoreDataProvider();
    }

    /**
     * @param array<string> $options
     *
     * @return array<string>
     */
    protected function overwriteCollectionDefaultEntryType(array $options): array
    {
        if ($options['entry_type'] === TextType::class) {
            $options['entry_type'] = MoneyType::class;
        }

        return $options;
    }
}
