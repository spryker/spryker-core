<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Communication\Form\Type;

use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Generated\Shared\Transfer\DiscountMoneyAmountTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Spryker\Zed\Kernel\Communication\Form\AbstractCollectionType;

/**
 * @method \Spryker\Zed\Money\Communication\MoneyCommunicationFactory getFactory()
 */
class MoneyCollectionType extends AbstractCollectionType
{

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
                'data_class' => MoneyValueTransfer::class
            ]
        ];

        $options['entry_type'] = MoneyType::class;

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $this->setInitialMoneyValueData($event);
            }
        );

        parent::buildForm($builder, array_merge_recursive($defaultOptions, $options));
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    protected function setInitialMoneyValueData(FormEvent $event)
    {
        if (count($event->getData()) === 0) {
            $event->setData(
                $this->getFactory()->createMoneyCollectionDataProvider()->getInitialData()
            );
            return;
        }

        $event->setData( $this->getFactory()->createMoneyCollectionDataProvider()->getMissingValues($event->getData()));
    }
}
