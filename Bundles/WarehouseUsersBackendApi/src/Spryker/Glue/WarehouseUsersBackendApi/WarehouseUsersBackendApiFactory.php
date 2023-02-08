<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Spryker\Glue\WarehouseUsersBackendApi\Dependency\Facade\WarehouseUsersBackendApiToUserFacadeInterface;
use Spryker\Glue\WarehouseUsersBackendApi\Dependency\Facade\WarehouseUsersBackendApiToWarehouseUserFacadeInterface;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Creator\ResponseCreator;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Creator\ResponseCreatorInterface;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Creator\WarehouseUserAssignmentCreator;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Creator\WarehouseUserAssignmentCreatorInterface;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Deleter\WarehouseUserAssignmentDeleter;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Deleter\WarehouseUserAssignmentDeleterInterface;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Mapper\WarehouseUserAssignmentMapper;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Mapper\WarehouseUserAssignmentMapperInterface;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Reader\UserReader;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Reader\UserReaderInterface;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Reader\WarehouseUserAssignmentReader;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Reader\WarehouseUserAssignmentReaderInterface;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Updater\WarehouseUserAssignmentUpdater;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Updater\WarehouseUserAssignmentUpdaterInterface;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Validator\WarehouseUserAssignmentValidator;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Validator\WarehouseUserAssignmentValidatorInterface;

/**
 * @method \Spryker\Glue\WarehouseUsersBackendApi\WarehouseUsersBackendApiConfig getConfig()
 */
class WarehouseUsersBackendApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\WarehouseUsersBackendApi\Processor\Reader\WarehouseUserAssignmentReaderInterface
     */
    public function createWarehouseUserAssignmentReader(): WarehouseUserAssignmentReaderInterface
    {
        return new WarehouseUserAssignmentReader(
            $this->getWarehouseUserFacade(),
            $this->createUserReader(),
            $this->createResponseCreator(),
        );
    }

    /**
     * @return \Spryker\Glue\WarehouseUsersBackendApi\Processor\Creator\WarehouseUserAssignmentCreatorInterface
     */
    public function createWarehouseUserAssignmentCreator(): WarehouseUserAssignmentCreatorInterface
    {
        return new WarehouseUserAssignmentCreator(
            $this->getWarehouseUserFacade(),
            $this->createWarehouseUserAssignmentValidator(),
            $this->createWarehouseUserAssignmentMapper(),
            $this->createResponseCreator(),
        );
    }

    /**
     * @return \Spryker\Glue\WarehouseUsersBackendApi\Processor\Updater\WarehouseUserAssignmentUpdaterInterface
     */
    public function createWarehouseUserAssignmentUpdater(): WarehouseUserAssignmentUpdaterInterface
    {
        return new WarehouseUserAssignmentUpdater(
            $this->getWarehouseUserFacade(),
            $this->createWarehouseUserAssignmentValidator(),
            $this->createWarehouseUserAssignmentMapper(),
            $this->createResponseCreator(),
        );
    }

    /**
     * @return \Spryker\Glue\WarehouseUsersBackendApi\Processor\Deleter\WarehouseUserAssignmentDeleterInterface
     */
    public function createWarehouseUserAssignmentDeleter(): WarehouseUserAssignmentDeleterInterface
    {
        return new WarehouseUserAssignmentDeleter(
            $this->getWarehouseUserFacade(),
            $this->createWarehouseUserAssignmentValidator(),
            $this->createUserReader(),
            $this->createResponseCreator(),
        );
    }

    /**
     * @return \Spryker\Glue\WarehouseUsersBackendApi\Processor\Validator\WarehouseUserAssignmentValidatorInterface
     */
    public function createWarehouseUserAssignmentValidator(): WarehouseUserAssignmentValidatorInterface
    {
        return new WarehouseUserAssignmentValidator($this->createUserReader());
    }

    /**
     * @return \Spryker\Glue\WarehouseUsersBackendApi\Processor\Reader\UserReaderInterface
     */
    public function createUserReader(): UserReaderInterface
    {
        return new UserReader($this->getUserFacade());
    }

    /**
     * @return \Spryker\Glue\WarehouseUsersBackendApi\Processor\Creator\ResponseCreatorInterface
     */
    public function createResponseCreator(): ResponseCreatorInterface
    {
        return new ResponseCreator(
            $this->createWarehouseUserAssignmentMapper(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Glue\WarehouseUsersBackendApi\Processor\Mapper\WarehouseUserAssignmentMapperInterface
     */
    public function createWarehouseUserAssignmentMapper(): WarehouseUserAssignmentMapperInterface
    {
        return new WarehouseUserAssignmentMapper();
    }

    /**
     * @return \Spryker\Glue\WarehouseUsersBackendApi\Dependency\Facade\WarehouseUsersBackendApiToWarehouseUserFacadeInterface
     */
    public function getWarehouseUserFacade(): WarehouseUsersBackendApiToWarehouseUserFacadeInterface
    {
        return $this->getProvidedDependency(WarehouseUsersBackendApiDependencyProvider::FACADE_WAREHOUSE_USER);
    }

    /**
     * @return \Spryker\Glue\WarehouseUsersBackendApi\Dependency\Facade\WarehouseUsersBackendApiToUserFacadeInterface
     */
    public function getUserFacade(): WarehouseUsersBackendApiToUserFacadeInterface
    {
        return $this->getProvidedDependency(WarehouseUsersBackendApiDependencyProvider::FACADE_USER);
    }
}
