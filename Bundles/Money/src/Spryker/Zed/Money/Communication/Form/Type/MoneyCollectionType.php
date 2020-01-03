<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Communication\Form\Type;

use Countable;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractCollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\Money\Communication\MoneyCommunicationFactory getFactory()
 * @method \Spryker\Zed\Money\MoneyConfig getConfig()
 * @method \Spryker\Zed\Money\Business\MoneyFacadeInterface getFacade()
 */
class MoneyCollectionType extends AbstractCollectionType
{
    public const OPTION_AMOUNT_PER_STORE = 'amount_per_store';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = $this->overwriteCollectionDefaultEntryType($options);

        $defaultOptions = [
            'entry_options' => [
                'data_class' => MoneyValueTransfer::class,
            ],
        ];

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($options) {
                $this->setInitialMoneyValueData($event, $options);
            }
        );

        parent::buildForm($builder, array_replace_recursive($defaultOptions, $options));
    }

    /**
     * Configures the options for this type.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver The resolver for the options
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_AMOUNT_PER_STORE);

        $resolver->setDefaults([
           static::OPTION_AMOUNT_PER_STORE => true,
        ]);

        parent::configureOptions($resolver);
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @param string[] $options
     *
     * @return void
     */
    protected function setInitialMoneyValueData(FormEvent $event, array $options)
    {
        $moneyCollectionInitialDataProvider = $this->getFormDataProvider($options);

        if (!($event->getData() instanceof Countable) || count($event->getData()) === 0) {
            $event->setData(
                $moneyCollectionInitialDataProvider->getInitialData()
            );

            return;
        }

        /** @var \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[] $data */
        $data = $event->getData();
        $event->setData(
            $moneyCollectionInitialDataProvider->mergeMissingMoneyValues($data)
        );
    }

    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars[static::OPTION_AMOUNT_PER_STORE] = $options[static::OPTION_AMOUNT_PER_STORE];
    }

    /**
     * @param array $options
     *
     * @return \Spryker\Zed\Money\Communication\Form\DataProvider\MoneyCollectionDataProviderInterface
     */
    protected function getFormDataProvider(array $options)
    {
        if ($options[static::OPTION_AMOUNT_PER_STORE]) {
            return $this->getFactory()->createMoneyCollectionMultiStoreDataProvider();
        }

        return $this->getFactory()->createMoneyCollectionSingleStoreDataProvider();
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'money_collection';
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function overwriteCollectionDefaultEntryType(array $options)
    {
        if ($options['entry_type'] === TextType::class) {
            $options['entry_type'] = MoneyType::class;
        }

        return $options;
    }
}
