<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi\Processor\Reader;

use Generated\Shared\Transfer\GlueFilterTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentConditionsTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;
use Spryker\Glue\WarehouseUsersBackendApi\Dependency\Facade\WarehouseUsersBackendApiToWarehouseUserFacadeInterface;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Creator\ResponseCreatorInterface;
use Spryker\Glue\WarehouseUsersBackendApi\WarehouseUsersBackendApiConfig;

class WarehouseUserAssignmentReader implements WarehouseUserAssignmentReaderInterface
{
    /**
     * @var string
     */
    protected const FIELD_WAREHOUSE_UUID = 'warehouseUuid';

    /**
     * @var \Spryker\Glue\WarehouseUsersBackendApi\Dependency\Facade\WarehouseUsersBackendApiToWarehouseUserFacadeInterface
     */
    protected WarehouseUsersBackendApiToWarehouseUserFacadeInterface $warehouseUserFacade;

    /**
     * @var \Spryker\Glue\WarehouseUsersBackendApi\Processor\Reader\UserReaderInterface
     */
    protected UserReaderInterface $userReader;

    /**
     * @var \Spryker\Glue\WarehouseUsersBackendApi\Processor\Creator\ResponseCreatorInterface
     */
    protected ResponseCreatorInterface $responseCreator;

    /**
     * @param \Spryker\Glue\WarehouseUsersBackendApi\Dependency\Facade\WarehouseUsersBackendApiToWarehouseUserFacadeInterface $warehouseUserFacade
     * @param \Spryker\Glue\WarehouseUsersBackendApi\Processor\Reader\UserReaderInterface $userReader
     * @param \Spryker\Glue\WarehouseUsersBackendApi\Processor\Creator\ResponseCreatorInterface $responseCreator
     */
    public function __construct(
        WarehouseUsersBackendApiToWarehouseUserFacadeInterface $warehouseUserFacade,
        UserReaderInterface $userReader,
        ResponseCreatorInterface $responseCreator
    ) {
        $this->warehouseUserFacade = $warehouseUserFacade;
        $this->userReader = $userReader;
        $this->responseCreator = $responseCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getWarehouseUserAssignment(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        if (!$this->isRequestUserProvided($glueRequestTransfer)) {
            return $this->responseCreator->createWarehouseUserAssignmentNotFoundErrorResponse();
        }

        $userTransfer = $this->userReader->findUserTransferById(
            $glueRequestTransfer->getRequestUserOrFail()->getSurrogateIdentifierOrFail(),
        );
        if ($userTransfer === null) {
            return $this->responseCreator->createWarehouseUserAssignmentNotFoundErrorResponse();
        }

        $warehouseUserAssignmentCriteriaTransfer = $this->createWarehouseUserAssignmentCriteriaTransfer(
            $glueRequestTransfer,
            $userTransfer,
        );
        $warehouseUserAssignmentCollection = $this->warehouseUserFacade->getWarehouseUserAssignmentCollection(
            $warehouseUserAssignmentCriteriaTransfer,
        );

        if ($warehouseUserAssignmentCollection->getWarehouseUserAssignments()->count() === 0) {
            return $this->responseCreator->createWarehouseUserAssignmentNotFoundErrorResponse();
        }

        return $this->responseCreator->createWarehouseUserAssignmentResponse(
            $warehouseUserAssignmentCollection->getWarehouseUserAssignments(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getWarehouseUserAssignmentCollection(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        if (!$this->isRequestUserProvided($glueRequestTransfer)) {
            return $this->responseCreator->createWarehouseUserAssignmentForbiddenErrorResponse();
        }

        $userTransfer = $this->userReader->findUserTransferById(
            $glueRequestTransfer->getRequestUserOrFail()->getSurrogateIdentifierOrFail(),
        );
        if ($userTransfer === null) {
            return $this->responseCreator->createWarehouseUserAssignmentForbiddenErrorResponse();
        }

        $warehouseUserAssignmentCriteriaTransfer = $this->createWarehouseUserAssignmentCriteriaTransfer(
            $glueRequestTransfer,
            $userTransfer,
        );

        $warehouseUserAssignmentCollection = $this->warehouseUserFacade->getWarehouseUserAssignmentCollection(
            $warehouseUserAssignmentCriteriaTransfer,
        );

        return $this->responseCreator->createWarehouseUserAssignmentResponse(
            $warehouseUserAssignmentCollection->getWarehouseUserAssignments(),
            $warehouseUserAssignmentCollection->getPagination(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer
     */
    protected function createWarehouseUserAssignmentCriteriaTransfer(
        GlueRequestTransfer $glueRequestTransfer,
        UserTransfer $userTransfer
    ): WarehouseUserAssignmentCriteriaTransfer {
        $warehouseUserAssignmentCriteriaTransfer = new WarehouseUserAssignmentCriteriaTransfer();
        $warehouseUserAssignmentConditionsTransfer = new WarehouseUserAssignmentConditionsTransfer();

        if ($userTransfer->getIsWarehouseUser()) {
            $warehouseUserAssignmentConditionsTransfer->addUserUuid($userTransfer->getUuidOrFail());
        }

        if ($glueRequestTransfer->getResource() && $glueRequestTransfer->getResourceOrFail()->getId()) {
            $warehouseUserAssignmentConditionsTransfer->addUuid($glueRequestTransfer->getResourceOrFail()->getIdOrFail());

            return $warehouseUserAssignmentCriteriaTransfer->setWarehouseUserAssignmentConditions($warehouseUserAssignmentConditionsTransfer);
        }

        foreach ($glueRequestTransfer->getFilters() as $glueFilterTransfer) {
            $this->applyWarehouseUserAssignmentFilters($glueFilterTransfer, $warehouseUserAssignmentConditionsTransfer);
        }

        return $warehouseUserAssignmentCriteriaTransfer
            ->setPagination($glueRequestTransfer->getPagination())
            ->setSortCollection($glueRequestTransfer->getSortings())
            ->setWarehouseUserAssignmentConditions($warehouseUserAssignmentConditionsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueFilterTransfer $glueFilterTransfer
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentConditionsTransfer $warehouseUserAssignmentConditionsTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentConditionsTransfer
     */
    protected function applyWarehouseUserAssignmentFilters(
        GlueFilterTransfer $glueFilterTransfer,
        WarehouseUserAssignmentConditionsTransfer $warehouseUserAssignmentConditionsTransfer
    ): WarehouseUserAssignmentConditionsTransfer {
        if ($glueFilterTransfer->getResourceOrFail() !== WarehouseUsersBackendApiConfig::RESOURCE_TYPE_WAREHOUSE_USER_ASSIGNMENTS) {
            return $warehouseUserAssignmentConditionsTransfer;
        }

        if ($glueFilterTransfer->getField() === WarehouseUserAssignmentTransfer::UUID) {
            $warehouseUserAssignmentConditionsTransfer->addUuid($glueFilterTransfer->getValueOrFail());
        }

        if ($glueFilterTransfer->getField() === WarehouseUserAssignmentTransfer::USER_UUID) {
            $warehouseUserAssignmentConditionsTransfer->addUserUuid($glueFilterTransfer->getValueOrFail());
        }

        if ($glueFilterTransfer->getField() === WarehouseUserAssignmentTransfer::IS_ACTIVE) {
            $warehouseUserAssignmentConditionsTransfer->setIsActive(filter_var($glueFilterTransfer->getValueOrFail(), FILTER_VALIDATE_BOOLEAN));
        }

        if ($glueFilterTransfer->getField() === static::FIELD_WAREHOUSE_UUID) {
            $warehouseUserAssignmentConditionsTransfer->addWarehouseUuid($glueFilterTransfer->getValueOrFail());
        }

        return $warehouseUserAssignmentConditionsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return bool
     */
    protected function isRequestUserProvided(GlueRequestTransfer $glueRequestTransfer): bool
    {
        return $glueRequestTransfer->getRequestUser() && $glueRequestTransfer->getRequestUserOrFail()->getSurrogateIdentifier();
    }
}
