<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGuiExtension\Dependency\Plugin;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Expands FormView with data that needed for the form render.
 *
 * Use this plugin if some additional data must present in form view.
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
     * @return \Symfony\Component\Form\FormView
     */
    public function expand(FormView $view, FormInterface $form, array $options): FormView;
}
