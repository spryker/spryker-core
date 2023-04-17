<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UsersBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Spryker\Glue\UsersBackendApi\Dependency\Facade\UsersBackendApiToUserFacadeInterface;
use Spryker\Glue\UsersBackendApi\Processor\Expander\UserByWarehouseUserAssignmentResourceRelationshipExpander;
use Spryker\Glue\UsersBackendApi\Processor\Expander\UserByWarehouseUserAssignmentResourceRelationshipExpanderInterface;
use Spryker\Glue\UsersBackendApi\Processor\Filter\WarehouseUserAssignmentResourceFilter;
use Spryker\Glue\UsersBackendApi\Processor\Filter\WarehouseUserAssignmentResourceFilterInterface;
use Spryker\Glue\UsersBackendApi\Processor\Mapper\UserResourceMapper;
use Spryker\Glue\UsersBackendApi\Processor\Mapper\UserResourceMapperInterface;
use Spryker\Glue\UsersBackendApi\Processor\Reader\UserResourceReader;
use Spryker\Glue\UsersBackendApi\Processor\Reader\UserResourceReaderInterface;
use Spryker\Glue\UsersBackendApi\Processor\Reader\UserResourceRelationshipReader;
use Spryker\Glue\UsersBackendApi\Processor\Reader\UserResourceRelationshipReaderInterface;

class UsersBackendApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\UsersBackendApi\Processor\Expander\UserByWarehouseUserAssignmentResourceRelationshipExpanderInterface
     */
    public function createUserByWarehouseUserAssignmentResourceRelationshipExpander(): UserByWarehouseUserAssignmentResourceRelationshipExpanderInterface
    {
        return new UserByWarehouseUserAssignmentResourceRelationshipExpander(
            $this->createWarehouseUserAssignmentResourceFilter(),
            $this->createUserResourceRelationshipReader(),
        );
    }

    /**
     * @return \Spryker\Glue\UsersBackendApi\Processor\Filter\WarehouseUserAssignmentResourceFilterInterface
     */
    public function createWarehouseUserAssignmentResourceFilter(): WarehouseUserAssignmentResourceFilterInterface
    {
        return new WarehouseUserAssignmentResourceFilter();
    }

    /**
     * @return \Spryker\Glue\UsersBackendApi\Processor\Reader\UserResourceRelationshipReaderInterface
     */
    public function createUserResourceRelationshipReader(): UserResourceRelationshipReaderInterface
    {
        return new UserResourceRelationshipReader(
            $this->createUserResourceReader(),
        );
    }

    /**
     * @return \Spryker\Glue\UsersBackendApi\Processor\Reader\UserResourceReaderInterface
     */
    public function createUserResourceReader(): UserResourceReaderInterface
    {
        return new UserResourceReader(
            $this->createUserResourceMapper(),
            $this->getUserFacade(),
        );
    }

    /**
     * @return \Spryker\Glue\UsersBackendApi\Processor\Mapper\UserResourceMapperInterface
     */
    public function createUserResourceMapper(): UserResourceMapperInterface
    {
        return new UserResourceMapper();
    }

    /**
     * @return \Spryker\Glue\UsersBackendApi\Dependency\Facade\UsersBackendApiToUserFacadeInterface
     */
    public function getUserFacade(): UsersBackendApiToUserFacadeInterface
    {
        return $this->getProvidedDependency(UsersBackendApiDependencyProvider::FACADE_USER);
    }
}
