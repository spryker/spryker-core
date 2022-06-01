<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Communication\Plugin\CompanyUnitAddressGui;

use Spryker\Zed\CompanyUnitAddressGuiExtension\Dependency\Plugin\CompanyUnitAddressEditFormExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CompanyGui\Communication\CompanyGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CompanyGui\CompanyGuiConfig getConfig()
 */
class CompanyToCompanyUnitAddressEditFormExpanderPlugin extends AbstractPlugin implements CompanyUnitAddressEditFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `CompanyUnitAddressForm` with a `fkCompany` form field as an input box with ajax search and suggestions.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder): void
    {
        $this->getFactory()
            ->createCompanyToCompanyUnitAddressFormExpander()
            ->expand($builder);
    }
}
