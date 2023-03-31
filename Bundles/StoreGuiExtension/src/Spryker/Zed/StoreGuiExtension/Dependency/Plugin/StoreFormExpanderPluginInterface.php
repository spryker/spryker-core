<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\StoreTransfer;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Provides extension capabilities for StoreGui Module
 *
 * Use this plugin when you need to expand a Store Form
 */
interface StoreFormExpanderPluginInterface
{
    /**
     * Specification:
     * - Adds form parts to the main form builder.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface> $builder
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface>
     */
    public function expand(FormBuilderInterface $builder, StoreTransfer $storeTransfer): FormBuilderInterface;
}
