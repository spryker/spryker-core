<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsUsersBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Spryker\Glue\PickingListsUsersBackendApi\Dependency\Facade\PickingListsUsersBackendApiToPickingListFacadeInterface;
use Spryker\Glue\PickingListsUsersBackendApi\Dependency\Resource\PickingListsUsersBackendApiToUsersBackendApiResourceInterface;
use Spryker\Glue\PickingListsUsersBackendApi\Processor\Expander\PickingListUsersResourceRelationshipExpander;
use Spryker\Glue\PickingListsUsersBackendApi\Processor\Expander\PickingListUsersResourceRelationshipExpanderInterface;
use Spryker\Glue\PickingListsUsersBackendApi\Processor\Filter\PickingListResourceFilter;
use Spryker\Glue\PickingListsUsersBackendApi\Processor\Filter\PickingListResourceFilterInterface;
use Spryker\Glue\PickingListsUsersBackendApi\Processor\Reader\PickingListUsersResourceRelationshipReader;
use Spryker\Glue\PickingListsUsersBackendApi\Processor\Reader\PickingListUsersResourceRelationshipReaderInterface;

class PickingListsUsersBackendApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\PickingListsUsersBackendApi\Processor\Expander\PickingListUsersResourceRelationshipExpanderInterface
     */
    public function createPickingListUsersResourceRelationshipExpander(): PickingListUsersResourceRelationshipExpanderInterface
    {
        return new PickingListUsersResourceRelationshipExpander(
            $this->createPickingListResourceFilter(),
            $this->createPickingListUsersResourceRelationshipReader(),
        );
    }

    /**
     * @return \Spryker\Glue\PickingListsUsersBackendApi\Processor\Reader\PickingListUsersResourceRelationshipReaderInterface
     */
    public function createPickingListUsersResourceRelationshipReader(): PickingListUsersResourceRelationshipReaderInterface
    {
        return new PickingListUsersResourceRelationshipReader(
            $this->getPickingListFacade(),
            $this->getUsersBackendApiResource(),
        );
    }

    /**
     * @return \Spryker\Glue\PickingListsUsersBackendApi\Processor\Filter\PickingListResourceFilterInterface
     */
    public function createPickingListResourceFilter(): PickingListResourceFilterInterface
    {
        return new PickingListResourceFilter();
    }

    /**
     * @return \Spryker\Glue\PickingListsUsersBackendApi\Dependency\Resource\PickingListsUsersBackendApiToUsersBackendApiResourceInterface
     */
    public function getUsersBackendApiResource(): PickingListsUsersBackendApiToUsersBackendApiResourceInterface
    {
        return $this->getProvidedDependency(PickingListsUsersBackendApiDependencyProvider::RESOURCE_USERS_BACKEND_API);
    }

    /**
     * @return \Spryker\Glue\PickingListsUsersBackendApi\Dependency\Facade\PickingListsUsersBackendApiToPickingListFacadeInterface
     */
    public function getPickingListFacade(): PickingListsUsersBackendApiToPickingListFacadeInterface
    {
        return $this->getProvidedDependency(PickingListsUsersBackendApiDependencyProvider::FACADE_PICKING_LIST);
    }
}
