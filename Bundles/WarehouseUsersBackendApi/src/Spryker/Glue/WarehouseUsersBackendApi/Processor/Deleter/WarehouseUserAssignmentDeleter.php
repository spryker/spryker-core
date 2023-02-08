<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi\Processor\Deleter;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentConditionsTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;
use Spryker\Glue\WarehouseUsersBackendApi\Dependency\Facade\WarehouseUsersBackendApiToWarehouseUserFacadeInterface;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Creator\ResponseCreatorInterface;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Reader\UserReaderInterface;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Validator\WarehouseUserAssignmentValidatorInterface;

class WarehouseUserAssignmentDeleter implements WarehouseUserAssignmentDeleterInterface
{
    /**
     * @var \Spryker\Glue\WarehouseUsersBackendApi\Dependency\Facade\WarehouseUsersBackendApiToWarehouseUserFacadeInterface
     */
    protected WarehouseUsersBackendApiToWarehouseUserFacadeInterface $warehouseUserFacade;

    /**
     * @var \Spryker\Glue\WarehouseUsersBackendApi\Processor\Validator\WarehouseUserAssignmentValidatorInterface
     */
    protected WarehouseUserAssignmentValidatorInterface $warehouseUserAssignmentValidator;

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
     * @param \Spryker\Glue\WarehouseUsersBackendApi\Processor\Validator\WarehouseUserAssignmentValidatorInterface $warehouseUserAssignmentValidator
     * @param \Spryker\Glue\WarehouseUsersBackendApi\Processor\Reader\UserReaderInterface $userReader
     * @param \Spryker\Glue\WarehouseUsersBackendApi\Processor\Creator\ResponseCreatorInterface $responseCreator
     */
    public function __construct(
        WarehouseUsersBackendApiToWarehouseUserFacadeInterface $warehouseUserFacade,
        WarehouseUserAssignmentValidatorInterface $warehouseUserAssignmentValidator,
        UserReaderInterface $userReader,
        ResponseCreatorInterface $responseCreator
    ) {
        $this->warehouseUserFacade = $warehouseUserFacade;
        $this->warehouseUserAssignmentValidator = $warehouseUserAssignmentValidator;
        $this->userReader = $userReader;
        $this->responseCreator = $responseCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function deleteWarehouseUserAssignment(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $warehouseUserAssignmentTransfer = $this->findWarehouseUserAssignmentTransfer(
            $glueRequestTransfer->getResourceOrFail()->getIdOrFail(),
        );
        if (!$warehouseUserAssignmentTransfer) {
            return $this->responseCreator->createWarehouseUserAssignmentNotFoundErrorResponse();
        }

        $isCurrentUserAllowedToOperate = $this->warehouseUserAssignmentValidator->isCurrentUserAllowedToOperateWithWarehouseUserAssignment(
            $glueRequestTransfer,
            $warehouseUserAssignmentTransfer->getUserUuidOrFail(),
        );
        if (!$isCurrentUserAllowedToOperate) {
            return $this->responseCreator->createWarehouseUserAssignmentNotFoundErrorResponse();
        }

        $warehouseUserAssignmentCollectionDeleteCriteriaTransfer = $this->createWarehouseUserAssignmentCollectionDeleteCriteriaTransfer(
            $glueRequestTransfer,
        );
        $warehouseUserAssignmentCollectionResponseTransfer = $this->warehouseUserFacade->deleteWarehouseUserAssignmentCollection(
            $warehouseUserAssignmentCollectionDeleteCriteriaTransfer,
        );

        if ($warehouseUserAssignmentCollectionResponseTransfer->getErrors()->count() !== 0) {
            return $this->responseCreator->createWarehouseUserAssignmentErrorResponse(
                $warehouseUserAssignmentCollectionResponseTransfer->getErrors(),
            );
        }

        return $this->responseCreator->createNoContentResponse();
    }

    /**
     * @param string $warehouseUserAssignmentUuid
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer|null
     */
    protected function findWarehouseUserAssignmentTransfer(string $warehouseUserAssignmentUuid): ?WarehouseUserAssignmentTransfer
    {
        $warehouseUserAssignmentConditionsTransfer = (new WarehouseUserAssignmentConditionsTransfer())->addUuid($warehouseUserAssignmentUuid);
        $warehouseUserAssignmentCriteriaTransfer = (new WarehouseUserAssignmentCriteriaTransfer())->setWarehouseUserAssignmentConditions($warehouseUserAssignmentConditionsTransfer);

        $warehouseUserAssignmentCollectionTransfer = $this->warehouseUserFacade->getWarehouseUserAssignmentCollection($warehouseUserAssignmentCriteriaTransfer);

        $warehouseUserAssignmentTransfers = $warehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments();
        if ($warehouseUserAssignmentTransfers->count() === 1) {
            return $warehouseUserAssignmentTransfers->getIterator()->current();
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionDeleteCriteriaTransfer
     */
    protected function createWarehouseUserAssignmentCollectionDeleteCriteriaTransfer(
        GlueRequestTransfer $glueRequestTransfer
    ): WarehouseUserAssignmentCollectionDeleteCriteriaTransfer {
        return (new WarehouseUserAssignmentCollectionDeleteCriteriaTransfer())->addUuid(
            $glueRequestTransfer->getResourceOrFail()->getIdOrFail(),
        );
    }
}
