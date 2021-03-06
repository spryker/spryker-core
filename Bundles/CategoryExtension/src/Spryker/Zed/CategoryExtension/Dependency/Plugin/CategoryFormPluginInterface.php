<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryExtension\Dependency\Plugin;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\CategoryGuiExtension\Dependency\Plugin\CategoryFormPluginInterface} instead.
 */
interface CategoryFormPluginInterface
{
    /**
     * Specification:
     * - Add form parts to the main form builder.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder);
}
