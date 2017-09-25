<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Communication\Form\Type;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractCollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\Money\Communication\MoneyCommunicationFactory getFactory()
 */
class MoneyCollectionType extends AbstractCollectionType
{

    const OPTION_AMOUNT_PER_STORE = 'amount_per_store';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $defaultOptions = [
            'entry_options' => [
                'data_class' => MoneyValueTransfer::class,
            ],
        ];

        $options['entry_type'] = MoneyType::class;

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($options) {
                $this->setInitialMoneyValueData($event, $options);
            }
        );

        parent::buildForm($builder, array_merge_recursive($defaultOptions, $options));
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
        $moneyCollectionInitialDataProvider = $this->getFactory()->createMoneyCollectionDataProvider();
        if (count($event->getData()) === 0) {
            $event->setData(
                $moneyCollectionInitialDataProvider->getInitialData($options)
            );
            return;
        }

        $event->setData(
            $moneyCollectionInitialDataProvider->mergeMissingMoneyValues(
                $event->getData(),
                $options
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars[static::OPTION_AMOUNT_PER_STORE] = $options[static::OPTION_AMOUNT_PER_STORE];
    }

}
