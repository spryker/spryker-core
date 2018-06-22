<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGuiExtension\Dependency\Plugin;

use Symfony\Component\Form\FormBuilderInterface;

interface ProductListCreateFormExpanderPluginInterface
{
    public const OPTION_DISABLE_GENERAL = 'OPTION_DISABLE_GENERAL';

    /**
     * Specification:
     * - Get name, that used in form building.
     *
     * @api
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Specification:
     * - Expands \Spryker\Zed\ProductListGui\Communication\Form\ProductListForm with new form.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void;

    /**
     * Specification:
     * - Update form options, if needed.
     *
     * @api
     *
     * @param array $options
     *
     * @return array
     */
    public function getOptions(array $options): array;
}
