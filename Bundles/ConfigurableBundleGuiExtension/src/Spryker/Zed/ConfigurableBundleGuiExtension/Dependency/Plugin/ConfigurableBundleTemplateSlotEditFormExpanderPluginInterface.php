<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin;

use Symfony\Component\Form\FormBuilderInterface;

interface ConfigurableBundleTemplateSlotEditFormExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands ConfigurableBundleTemplateSlotEditForm with new form fields or subforms.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function expand(FormBuilderInterface $builder, array $options): void;
}
