<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagementExtension\Dependency\Plugin;

use Symfony\Component\Form\FormBuilderInterface;

interface ProductAbstractFormExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands ProductFormAdd with new form.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder): FormBuilderInterface;
}
