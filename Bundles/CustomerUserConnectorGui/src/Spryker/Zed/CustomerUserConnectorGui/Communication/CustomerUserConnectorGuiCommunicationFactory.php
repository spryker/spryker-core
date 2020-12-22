<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnectorGui\Communication;

use Generated\Shared\Transfer\CustomerUserConnectionUpdateTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\CustomerUserConnectorGui\Communication\Form\CustomerUserConnectorForm;
use Spryker\Zed\CustomerUserConnectorGui\Communication\Form\DataProvider\CustomerUserConnectorFormDataProvider;
use Spryker\Zed\CustomerUserConnectorGui\Communication\Table\AssignedCustomerTable;
use Spryker\Zed\CustomerUserConnectorGui\Communication\Table\AvailableCustomerTable;
use Spryker\Zed\CustomerUserConnectorGui\CustomerUserConnectorGuiDependencyProvider;
use Spryker\Zed\CustomerUserConnectorGui\Dependency\Service\CustomerUserConnectorGuiToUtilSanitizeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CustomerUserConnectorGui\CustomerUserConnectorGuiConfig getConfig()
 */
class CustomerUserConnectorGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Spryker\Zed\CustomerUserConnectorGui\Communication\Table\AssignedCustomerTable
     */
    public function createAssignedCustomerTable(UserTransfer $userTransfer)
    {
        return new AssignedCustomerTable(
            $this->getProvidedDependency(CustomerUserConnectorGuiDependencyProvider::QUERY_CONTAINER_CUSTOMER),
            $userTransfer,
            $this->getUtilSanitizeServiceInterface()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Spryker\Zed\CustomerUserConnectorGui\Communication\Table\AvailableCustomerTable
     */
    public function createAvailableCustomerTable(UserTransfer $userTransfer)
    {
        return new AvailableCustomerTable(
            $this->getProvidedDependency(CustomerUserConnectorGuiDependencyProvider::QUERY_CONTAINER_CUSTOMER),
            $userTransfer,
            $this->getUtilSanitizeServiceInterface()
        );
    }

    /**
     * @param int $idUser
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCustomerUserConnectorForm($idUser)
    {
        return $this->getFormFactory()->create(
            CustomerUserConnectorForm::class,
            $this->createUserConnectionTransfer($idUser),
            $this->createCustomerUserConnectorFormDataProvider()->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\CustomerUserConnector\Business\CustomerUserConnectorFacadeInterface
     */
    public function getCustomerUserConnectorFacade()
    {
        return $this->getProvidedDependency(CustomerUserConnectorGuiDependencyProvider::FACADE_CUSTOMER_USER_CONNECTOR);
    }

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\CustomerUserConnectionUpdateTransfer
     */
    protected function createUserConnectionTransfer($idUser)
    {
        return (new CustomerUserConnectionUpdateTransfer())->setIdUser($idUser);
    }

    /**
     * @return \Spryker\Zed\CustomerUserConnectorGui\Communication\Form\DataProvider\CustomerUserConnectorFormDataProvider
     */
    protected function createCustomerUserConnectorFormDataProvider()
    {
        return new CustomerUserConnectorFormDataProvider();
    }

    /**
     * @return \Spryker\Zed\CustomerUserConnectorGui\Dependency\QueryContainer\CustomerUserConnectorGuiToUserQueryContainerInterface
     */
    public function getUserQueryContainer()
    {
        return $this->getProvidedDependency(CustomerUserConnectorGuiDependencyProvider::QUERY_CONTAINER_USER);
    }

    /**
     * @return \Spryker\Zed\CustomerUserConnectorGui\Dependency\Service\CustomerUserConnectorGuiToUtilSanitizeInterface
     */
    protected function getUtilSanitizeServiceInterface(): CustomerUserConnectorGuiToUtilSanitizeInterface
    {
        return $this->getProvidedDependency(CustomerUserConnectorGuiDependencyProvider::SERVICE_UTIL_SANITIZE);
    }
}
