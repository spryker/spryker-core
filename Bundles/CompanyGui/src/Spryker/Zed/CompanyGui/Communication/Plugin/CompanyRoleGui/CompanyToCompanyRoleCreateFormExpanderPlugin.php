<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Communication\Plugin\CompanyRoleGui;

use Spryker\Zed\CompanyRoleGuiExtension\Communication\Plugin\CompanyRoleCreateFormExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CompanyGui\Communication\CompanyGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CompanyGui\CompanyGuiConfig getConfig()
 */
class CompanyToCompanyRoleCreateFormExpanderPlugin extends AbstractPlugin implements CompanyRoleCreateFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `CompanyRoleCreateForm` with a `fkCompany` form field as an input box with AJAX search and suggestions.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder): FormBuilderInterface
    {
        return $this->getFactory()
            ->createCompanyToCompanyRoleCreateFormExpander()
            ->expand($builder);
    }
}
