<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGuiExtension\Communication\Plugin;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Provides form expansion capabilities.
 *
 * Use this plugin interface for expanding `CompanyRoleCreateForm` with new form fields.
 */
interface CompanyRoleCreateFormExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands CompanyRoleCreateForm.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return \Symfony\Component\Form\FormBuilderInterface<mixed>
     */
    public function expand(FormBuilderInterface $builder): FormBuilderInterface;
}
