<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Expander;

use Symfony\Component\Form\FormBuilderInterface;

interface ConfigurableBundleTemplateSlotEditFormExpanderInterface
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function expandWithPlugins(FormBuilderInterface $builder, array $options): void;
}
