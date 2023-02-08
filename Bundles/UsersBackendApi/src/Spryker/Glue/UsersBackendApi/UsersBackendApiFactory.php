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
use Spryker\Glue\UsersBackendApi\Processor\Reader\UserReader;
use Spryker\Glue\UsersBackendApi\Processor\Reader\UserReaderInterface;

class UsersBackendApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\UsersBackendApi\Processor\Expander\UserByWarehouseUserAssignmentResourceRelationshipExpanderInterface
     */
    public function createUserByWarehouseUserAssignmentResourceRelationshipExpander(): UserByWarehouseUserAssignmentResourceRelationshipExpanderInterface
    {
        return new UserByWarehouseUserAssignmentResourceRelationshipExpander($this->createUserReader());
    }

    /**
     * @return \Spryker\Glue\UsersBackendApi\Processor\Reader\UserReaderInterface
     */
    public function createUserReader(): UserReaderInterface
    {
        return new UserReader($this->getUserFacade());
    }

    /**
     * @return \Spryker\Glue\UsersBackendApi\Dependency\Facade\UsersBackendApiToUserFacadeInterface
     */
    public function getUserFacade(): UsersBackendApiToUserFacadeInterface
    {
        return $this->getProvidedDependency(UsersBackendApiDependencyProvider::FACADE_USER);
    }
}
