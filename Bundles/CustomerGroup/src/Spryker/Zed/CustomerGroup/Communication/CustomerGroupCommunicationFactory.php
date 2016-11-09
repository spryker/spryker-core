<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication;

use Spryker\Zed\CustomerGroup\Communication\Form\CustomerGroupForm;
use Spryker\Zed\CustomerGroup\Communication\Form\CustomerUpdateForm;
use Spryker\Zed\CustomerGroup\Communication\Form\DataProvider\CustomerFormDataProvider;
use Spryker\Zed\CustomerGroup\Communication\Form\DataProvider\CustomerUpdateFormDataProvider;
use Spryker\Zed\CustomerGroup\Communication\Table\CustomerGroupTable;
use Spryker\Zed\CustomerGroup\CustomerGroupDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainer getQueryContainer()
 * @method \Spryker\Zed\CustomerGroup\CustomerGroupConfig getConfig()
 */
class CustomerGroupCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\CustomerGroup\Communication\Table\CustomerGroupTable
     */
    public function createCustomerGroupTable()
    {
        return new CustomerGroupTable(
            $this->getQueryContainer(),
            $this->getProvidedDependency(CustomerGroupDependencyProvider::SERVICE_DATE_FORMATTER)
        );
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCustomerGroupForm(array $data = [], array $options = [])
    {
        $customerFormType = new CustomerGroupForm($this->getQueryContainer());

        return $this->getFormFactory()->create($customerFormType, $data, $options);
    }

    /**
     * @return \Spryker\Zed\CustomerGroup\Communication\Form\DataProvider\CustomerFormDataProvider
     */
    public function createCustomerFormDataProvider()
    {
        return new CustomerFormDataProvider($this->getQueryContainer());
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCustomerUpdateForm(array $data = [], array $options = [])
    {
        $customerFormType = new CustomerUpdateForm($this->getQueryContainer());

        return $this->getFormFactory()->create($customerFormType, $data, $options);
    }

    /**
     * @return \Spryker\Zed\CustomerGroup\Communication\Form\DataProvider\CustomerUpdateFormDataProvider
     */
    public function createCustomerUpdateFormDataProvider()
    {
        return new CustomerUpdateFormDataProvider($this->getQueryContainer());
    }

}
