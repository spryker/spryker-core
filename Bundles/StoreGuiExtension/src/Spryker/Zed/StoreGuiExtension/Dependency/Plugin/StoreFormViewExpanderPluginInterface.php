<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\StoreTransfer;
use Symfony\Component\Form\FormView;

/**
 * Provides extension capabilities for StoreGui Module
 *
 * Use this plugin when you need to expand a Store Form View
 */
interface StoreFormViewExpanderPluginInterface
{
    /**
     * Specification:
     * - Adds variables available in template.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormView $formView
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Symfony\Component\Form\FormView
     */
    public function expandTemplateVariables(FormView $formView, StoreTransfer $storeTransfer): FormView;
}
