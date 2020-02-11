<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Expander;

use Symfony\Component\Form\FormBuilderInterface;

class ConfigurableBundleTemplateSlotEditFormExpander implements ConfigurableBundleTemplateSlotEditFormExpanderInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditFormExpanderPluginInterface[]
     */
    protected $configurableBundleTemplateSlotEditFormExpanderPlugins;

    /**
     * @param \Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditFormExpanderPluginInterface[] $configurableBundleTemplateSlotEditFormExpanderPlugins
     */
    public function __construct(array $configurableBundleTemplateSlotEditFormExpanderPlugins)
    {
        $this->configurableBundleTemplateSlotEditFormExpanderPlugins = $configurableBundleTemplateSlotEditFormExpanderPlugins;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function executeExpanderPlugins(FormBuilderInterface $builder, array $options): void
    {
        foreach ($this->configurableBundleTemplateSlotEditFormExpanderPlugins as $configurableBundleTemplateSlotEditFormExpanderPlugin) {
            $configurableBundleTemplateSlotEditFormExpanderPlugin->expand($builder, $options);
        }
    }
}
