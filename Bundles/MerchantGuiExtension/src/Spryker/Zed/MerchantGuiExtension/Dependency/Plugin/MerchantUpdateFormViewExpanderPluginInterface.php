<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGuiExtension\Dependency\Plugin;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Interface MerchantUpdateFormViewExpanderPluginInterface
 *
 *  Specification:
 * - Expands FormView with data that can be fetched from Form obj usually in Presentation layer
 *
 * @package Spryker\Zed\MerchantGuiExtension\Dependency\Plugin
 */
interface MerchantUpdateFormViewExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands FormView with data
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     *
     * @return void
     */
    public function expand(FormView $view, FormInterface $form, array $options): void;
}
