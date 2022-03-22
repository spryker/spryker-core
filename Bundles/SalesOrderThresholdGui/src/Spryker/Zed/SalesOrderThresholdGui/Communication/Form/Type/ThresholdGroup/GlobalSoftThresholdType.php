<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup;

use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\GlobalThresholdType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\SalesOrderThresholdGui\Communication\SalesOrderThresholdGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesOrderThresholdGui\SalesOrderThresholdGuiConfig getConfig()
 */
class GlobalSoftThresholdType extends AbstractGlobalThresholdType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addStrategyField($builder, $options[GlobalThresholdType::OPTION_SOFT_TYPES_ARRAY]);
        $this->addThresholdValueField($builder, $options);
        $this->addLocalizedForms($builder);
        $this->setPlaceholderForEmptySoftThreshold($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(GlobalThresholdType::OPTION_SOFT_TYPES_ARRAY);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function setPlaceholderForEmptySoftThreshold(FormBuilderInterface $builder): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var array<string, mixed> $softThreshold */
            $softThreshold = $event->getData();
            $idThreshold = $softThreshold[static::FIELD_ID_THRESHOLD] ?? null;

            if ($idThreshold === null) {
                $event->setData([]);
            }
        });
    }
}
