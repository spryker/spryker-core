<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Plugin\BusinessOnBehalfGui;

use Spryker\Zed\BusinessOnBehalfGuiExtension\Dependency\Plugin\CustomerBusinessUnitAttachFormExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Communication\CompanyBusinessUnitGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Business\CompanyBusinessUnitGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyBusinessUnitGui\CompanyBusinessUnitGuiConfig getConfig()
 */
class CompanyBusinessUnitToCustomerBusinessUnitAttachFormExpanderPlugin extends AbstractPlugin implements CustomerBusinessUnitAttachFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `CustomerBusinessUnitAttachForm` with a `fk_company_business_unit` form field as an input box with ajax search and suggestions.
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
            ->createCompanyBusinessUnitToCustomerBusinessUnitAttachFormExpander()
            ->expand($builder);
    }
}
