<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\LocalizedMessagesType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\SalesOrderThresholdGui\Communication\SalesOrderThresholdGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesOrderThresholdGui\SalesOrderThresholdGuiConfig getConfig()
 */
abstract class AbstractGlobalThresholdType extends AbstractType
{
    public const FIELD_ID_THRESHOLD = 'idThreshold';
    public const FIELD_STRATEGY = 'strategy';
    public const FIELD_THRESHOLD = 'threshold';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLocalizedForms(FormBuilderInterface $builder): self
    {
        $localeCollection = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        foreach ($localeCollection as $localeTransfer) {
            $this->addLocalizedForm($builder, $localeTransfer->getLocaleName());
        }

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    protected function addLocalizedForm(FormBuilderInterface $builder, string $name, array $options = []): self
    {
        $builder->add($name, LocalizedMessagesType::class, [
                'label' => false,
            ]);

        return $this;
    }
}
