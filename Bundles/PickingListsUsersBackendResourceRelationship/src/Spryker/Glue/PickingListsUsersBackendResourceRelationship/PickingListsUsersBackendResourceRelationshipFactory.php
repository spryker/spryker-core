<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsUsersBackendResourceRelationship;

use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Spryker\Glue\PickingListsUsersBackendResourceRelationship\Dependency\Facade\PickingListsUsersBackendResourceRelationshipToPickingListFacadeInterface;
use Spryker\Glue\PickingListsUsersBackendResourceRelationship\Dependency\Resource\PickingListsUsersBackendResourceRelationshipToUsersBackendApiResourceInterface;
use Spryker\Glue\PickingListsUsersBackendResourceRelationship\Processor\Expander\PickingListUsersResourceRelationshipExpander;
use Spryker\Glue\PickingListsUsersBackendResourceRelationship\Processor\Expander\PickingListUsersResourceRelationshipExpanderInterface;
use Spryker\Glue\PickingListsUsersBackendResourceRelationship\Processor\Filter\PickingListResourceFilter;
use Spryker\Glue\PickingListsUsersBackendResourceRelationship\Processor\Filter\PickingListResourceFilterInterface;
use Spryker\Glue\PickingListsUsersBackendResourceRelationship\Processor\Reader\PickingListUsersResourceRelationshipReader;
use Spryker\Glue\PickingListsUsersBackendResourceRelationship\Processor\Reader\PickingListUsersResourceRelationshipReaderInterface;

class PickingListsUsersBackendResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\PickingListsUsersBackendResourceRelationship\Processor\Expander\PickingListUsersResourceRelationshipExpanderInterface
     */
    public function createPickingListUsersResourceRelationshipExpander(): PickingListUsersResourceRelationshipExpanderInterface
    {
        return new PickingListUsersResourceRelationshipExpander(
            $this->createPickingListResourceFilter(),
            $this->createPickingListUsersResourceRelationshipReader(),
        );
    }

    /**
     * @return \Spryker\Glue\PickingListsUsersBackendResourceRelationship\Processor\Reader\PickingListUsersResourceRelationshipReaderInterface
     */
    public function createPickingListUsersResourceRelationshipReader(): PickingListUsersResourceRelationshipReaderInterface
    {
        return new PickingListUsersResourceRelationshipReader(
            $this->getPickingListFacade(),
            $this->getUsersBackendApiResource(),
        );
    }

    /**
     * @return \Spryker\Glue\PickingListsUsersBackendResourceRelationship\Processor\Filter\PickingListResourceFilterInterface
     */
    public function createPickingListResourceFilter(): PickingListResourceFilterInterface
    {
        return new PickingListResourceFilter();
    }

    /**
     * @return \Spryker\Glue\PickingListsUsersBackendResourceRelationship\Dependency\Resource\PickingListsUsersBackendResourceRelationshipToUsersBackendApiResourceInterface
     */
    public function getUsersBackendApiResource(): PickingListsUsersBackendResourceRelationshipToUsersBackendApiResourceInterface
    {
        return $this->getProvidedDependency(PickingListsUsersBackendResourceRelationshipDependencyProvider::RESOURCE_USERS_BACKEND_API);
    }

    /**
     * @return \Spryker\Glue\PickingListsUsersBackendResourceRelationship\Dependency\Facade\PickingListsUsersBackendResourceRelationshipToPickingListFacadeInterface
     */
    public function getPickingListFacade(): PickingListsUsersBackendResourceRelationshipToPickingListFacadeInterface
    {
        return $this->getProvidedDependency(PickingListsUsersBackendResourceRelationshipDependencyProvider::FACADE_PICKING_LIST);
    }
}
