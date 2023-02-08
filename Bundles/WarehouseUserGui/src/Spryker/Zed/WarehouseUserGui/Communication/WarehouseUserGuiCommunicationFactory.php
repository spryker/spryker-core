<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUserGui\Communication;

use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignmentQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\WarehouseUserGui\Communication\Expander\WarehouseUserAssignmentFormExpander;
use Spryker\Zed\WarehouseUserGui\Communication\Expander\WarehouseUserAssignmentFormExpanderInterface;
use Spryker\Zed\WarehouseUserGui\Communication\Expander\WarehouseUserAssignmentTableActionExpander;
use Spryker\Zed\WarehouseUserGui\Communication\Expander\WarehouseUserAssignmentTableActionExpanderInterface;
use Spryker\Zed\WarehouseUserGui\Communication\Form\DataProvider\WarehouseUserFormDataProvider;
use Spryker\Zed\WarehouseUserGui\Communication\Form\Transformer\ArrayToStringModelTransformer;
use Spryker\Zed\WarehouseUserGui\Communication\Form\WarehouseUserForm;
use Spryker\Zed\WarehouseUserGui\Communication\Table\AssignedWarehouseTable;
use Spryker\Zed\WarehouseUserGui\Communication\Table\AvailableWarehouseTable;
use Spryker\Zed\WarehouseUserGui\Dependency\Facade\WarehouseUserGuiToUserFacadeInterface;
use Spryker\Zed\WarehouseUserGui\Dependency\Facade\WarehouseUserGuiToWarehouseUserFacadeInterface;
use Spryker\Zed\WarehouseUserGui\Dependency\Service\WarehouseUserGuiToUtilEncodingServiceInterface;
use Spryker\Zed\WarehouseUserGui\Dependency\Service\WarehouseUserGuiToUtilSanitizeServiceInterface;
use Spryker\Zed\WarehouseUserGui\WarehouseUserGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\WarehouseUserGui\WarehouseUserGuiConfig getConfig()
 */
class WarehouseUserGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface<mixed>
     */
    public function createWarehouseUserForm(array $data = [], array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(WarehouseUserForm::class, $data, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Spryker\Zed\WarehouseUserGui\Communication\Table\AvailableWarehouseTable
     */
    public function createAvailableWarehouseTable(UserTransfer $userTransfer): AvailableWarehouseTable
    {
        return new AvailableWarehouseTable(
            $userTransfer,
            $this->getStockQuery(),
            $this->getUtilEncodingService(),
            $this->getUtilSanitizeService(),
            $this->getWarehouseUserAssignmentPropelQuery(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Spryker\Zed\WarehouseUserGui\Communication\Table\AssignedWarehouseTable
     */
    public function createAssignedWarehouseTable(UserTransfer $userTransfer): AssignedWarehouseTable
    {
        return new AssignedWarehouseTable(
            $userTransfer,
            $this->getStockQuery(),
            $this->getUtilEncodingService(),
            $this->getUtilSanitizeService(),
        );
    }

    /**
     * @return \Spryker\Zed\WarehouseUserGui\Communication\Expander\WarehouseUserAssignmentFormExpanderInterface
     */
    public function createWarehouseUserAssignmentFormExpander(): WarehouseUserAssignmentFormExpanderInterface
    {
        return new WarehouseUserAssignmentFormExpander();
    }

    /**
     * @return \Spryker\Zed\WarehouseUserGui\Communication\Form\Transformer\ArrayToStringModelTransformer
     */
    public function createArrayToStringModelTransformer(): ArrayToStringModelTransformer
    {
        return new ArrayToStringModelTransformer();
    }

    /**
     * @return \Spryker\Zed\WarehouseUserGui\Communication\Form\DataProvider\WarehouseUserFormDataProvider
     */
    public function createWarehouseUserFormDataProvider(): WarehouseUserFormDataProvider
    {
        return new WarehouseUserFormDataProvider();
    }

    /**
     * @return \Spryker\Zed\WarehouseUserGui\Communication\Expander\WarehouseUserAssignmentTableActionExpanderInterface
     */
    public function createWarehouseUserAssignmentTableActionExpander(): WarehouseUserAssignmentTableActionExpanderInterface
    {
        return new WarehouseUserAssignmentTableActionExpander();
    }

    /**
     * @return \Spryker\Zed\WarehouseUserGui\Dependency\Facade\WarehouseUserGuiToUserFacadeInterface
     */
    public function getUserFacade(): WarehouseUserGuiToUserFacadeInterface
    {
        return $this->getProvidedDependency(WarehouseUserGuiDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Spryker\Zed\WarehouseUserGui\Dependency\Facade\WarehouseUserGuiToWarehouseUserFacadeInterface
     */
    public function getWarehouseUserFacade(): WarehouseUserGuiToWarehouseUserFacadeInterface
    {
        return $this->getProvidedDependency(WarehouseUserGuiDependencyProvider::FACADE_WAREHOUSE_USER);
    }

    /**
     * @return \Spryker\Zed\WarehouseUserGui\Dependency\Service\WarehouseUserGuiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): WarehouseUserGuiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(WarehouseUserGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\WarehouseUserGui\Dependency\Service\WarehouseUserGuiToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService(): WarehouseUserGuiToUtilSanitizeServiceInterface
    {
        return $this->getProvidedDependency(WarehouseUserGuiDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return \Orm\Zed\Stock\Persistence\SpyStockQuery
     */
    public function getStockQuery(): SpyStockQuery
    {
        return $this->getProvidedDependency(WarehouseUserGuiDependencyProvider::PROPEL_QUERY_STOCK);
    }

    /**
     * @return \Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignmentQuery
     */
    public function getWarehouseUserAssignmentPropelQuery(): SpyWarehouseUserAssignmentQuery
    {
        return $this->getProvidedDependency(WarehouseUserGuiDependencyProvider::PROPEL_QUERY_WAREHOUSE_USER_ASSIGNMENT);
    }
}
