<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication;

use Spryker\Zed\CustomerGroup\Communication\Form\CustomerGroupForm;
use Spryker\Zed\CustomerGroup\Communication\Form\DataProvider\CustomerGroupFormDataProvider;
use Spryker\Zed\CustomerGroup\Communication\Table\CustomerGroupTable;
use Spryker\Zed\CustomerGroup\Communication\Table\CustomerTable;
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


    public function createCustomerTable($customerGroupTransfer)
    {
        return new CustomerTable(
            $this->getQueryContainer(),
            $customerGroupTransfer
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
        $idCustomerGroup = !empty($data['id_customer_group']) ? $data['id_customer_group'] : null;

        $customerFormType = new CustomerGroupForm($this->getQueryContainer(), $idCustomerGroup);

        return $this->getFormFactory()->create($customerFormType, $data, $options);
    }

    /**
     * @return \Spryker\Zed\CustomerGroup\Communication\Form\DataProvider\CustomerGroupFormDataProvider
     */
    public function createCustomerGroupFormDataProvider()
    {
        return new CustomerGroupFormDataProvider($this->getQueryContainer());
    }

}
