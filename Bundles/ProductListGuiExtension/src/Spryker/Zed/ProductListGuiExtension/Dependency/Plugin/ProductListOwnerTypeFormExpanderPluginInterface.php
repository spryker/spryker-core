<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGuiExtension\Dependency\Plugin;

use Symfony\Component\Form\FormBuilderInterface;

interface ProductListOwnerTypeFormExpanderPluginInterface
{
    /**
     * Specification:
     * - Providers owner type.
     *
     * @api
     *
     * @return string
     */
    public function getOwnerType(): string;

    /**
     * Specification:
     * - Adds form parts to the main form builder
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void;
}
