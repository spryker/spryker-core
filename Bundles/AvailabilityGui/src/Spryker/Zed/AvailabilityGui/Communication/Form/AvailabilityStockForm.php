<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Communication\Form;

use Generated\Shared\Transfer\StockProductTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\AvailabilityGui\Communication\AvailabilityGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\AvailabilityGui\AvailabilityGuiConfig getConfig()
 * @method \Spryker\Zed\AvailabilityGui\Persistence\AvailabilityGuiRepositoryInterface getRepository()
 */
class AvailabilityStockForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_STOCKS = 'stocks';

    /**
     * @var string
     */
    public const OPTION_LOCALE = 'locale';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addStockField($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            static::OPTION_LOCALE => null,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string> $options
     *
     * @return $this
     */
    protected function addStockField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_STOCKS, CollectionType::class, [
            'entry_type' => StockSubForm::class,
            'entry_options' => [
                'locale' => $options[static::OPTION_LOCALE],
                'data_class' => StockProductTransfer::class,
            ],
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'AvailabilityGui_stock';
    }

    /**
     * @deprecated Use {@link getBlockPrefix()} instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
