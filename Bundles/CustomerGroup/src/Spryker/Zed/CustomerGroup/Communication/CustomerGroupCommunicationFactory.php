<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication;

use Generated\Shared\Transfer\CustomerGroupTransfer;
use Spryker\Zed\CustomerGroup\Communication\Form\CustomerGroupForm;
use Spryker\Zed\CustomerGroup\Communication\Form\DataProvider\CustomerGroupFormDataProvider;
use Spryker\Zed\CustomerGroup\Communication\Table\Assignment\AssignedCustomerTable;
use Spryker\Zed\CustomerGroup\Communication\Table\Assignment\AssignmentCustomerQueryBuilder;
use Spryker\Zed\CustomerGroup\Communication\Table\Assignment\AvailableCustomerTable;
use Spryker\Zed\CustomerGroup\Communication\Table\CustomerGroupTable;
use Spryker\Zed\CustomerGroup\Communication\Table\CustomerTable;
use Spryker\Zed\CustomerGroup\Communication\Tabs\CustomerGroupFormTabs;
use Spryker\Zed\CustomerGroup\CustomerGroupDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CustomerGroup\CustomerGroupConfig getConfig()
 * @method \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupRepositoryInterface getRepository()
 * @method \Spryker\Zed\CustomerGroup\Business\CustomerGroupFacadeInterface getFacade()
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
            $this->getDateFormatterService()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @return \Spryker\Zed\CustomerGroup\Communication\Table\CustomerTable
     */
    public function createCustomerTable(CustomerGroupTransfer $customerGroupTransfer)
    {
        return new CustomerTable(
            $this->getQueryContainer(),
            $customerGroupTransfer
        );
    }

    /**
     * @param int|null $idCustomerGroup
     *
     * @return \Spryker\Zed\CustomerGroup\Communication\Table\Assignment\AssignedCustomerTable
     */
    public function createAssignedCustomerTable($idCustomerGroup = null)
    {
        return new AssignedCustomerTable(
            $this->createAssignmentCustomerQueryBuilder(),
            $this->getUtilEncodingService(),
            $idCustomerGroup
        );
    }

    /**
     * @param int|null $idCustomerGroup
     *
     * @return \Spryker\Zed\CustomerGroup\Communication\Table\Assignment\AvailableCustomerTable
     */
    public function createAvailableCustomerTable($idCustomerGroup = null)
    {
        return new AvailableCustomerTable(
            $this->createAssignmentCustomerQueryBuilder(),
            $this->getUtilEncodingService(),
            $idCustomerGroup
        );
    }

    /**
     * @return \Spryker\Zed\CustomerGroup\Communication\Table\Assignment\AssignmentCustomerQueryBuilder
     */
    protected function createAssignmentCustomerQueryBuilder()
    {
        return new AssignmentCustomerQueryBuilder(
            $this->getCustomerQueryContainer()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCustomerGroupForm(CustomerGroupTransfer $data, array $options = [])
    {
        $options[CustomerGroupForm::ID_CUSTOMER_GROUP] = $data->getIdCustomerGroup();

        return $this->getFormFactory()->create(CustomerGroupForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Tabs\TabsInterface
     */
    public function createCustomerGroupFormTabs()
    {
        return new CustomerGroupFormTabs();
    }

    /**
     * @return \Spryker\Zed\CustomerGroup\Communication\Form\DataProvider\CustomerGroupFormDataProvider
     */
    public function createCustomerGroupFormDataProvider()
    {
        return new CustomerGroupFormDataProvider($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    protected function getDateFormatterService()
    {
        return $this->getProvidedDependency(CustomerGroupDependencyProvider::SERVICE_DATE_FORMATTER);
    }

    /**
     * @return \Spryker\Zed\CustomerGroup\Dependency\QueryContainer\CustomerGroupToCustomerQueryContainerInterface
     */
    protected function getCustomerQueryContainer()
    {
        return $this->getProvidedDependency(CustomerGroupDependencyProvider::QUERY_CONTAINER_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\CustomerGroup\Dependency\Service\CustomerGroupToUtilEncodingInterface
     */
    protected function getUtilEncodingService()
    {
        return $this->getProvidedDependency(CustomerGroupDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
