<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi\Processor\Updater;

use Generated\Shared\Transfer\ApiWarehouseUserAssignmentsAttributesTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionRequestTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentConditionsTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;
use Spryker\Glue\WarehouseUsersBackendApi\Dependency\Facade\WarehouseUsersBackendApiToWarehouseUserFacadeInterface;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Creator\ResponseCreatorInterface;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Mapper\WarehouseUserAssignmentMapperInterface;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Validator\WarehouseUserAssignmentValidatorInterface;

class WarehouseUserAssignmentUpdater implements WarehouseUserAssignmentUpdaterInterface
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
     * @var \Spryker\Glue\WarehouseUsersBackendApi\Processor\Mapper\WarehouseUserAssignmentMapperInterface
     */
    protected WarehouseUserAssignmentMapperInterface $warehouseUserAssignmentMapper;

    /**
     * @var \Spryker\Glue\WarehouseUsersBackendApi\Processor\Creator\ResponseCreatorInterface
     */
    protected ResponseCreatorInterface $responseCreator;

    /**
     * @param \Spryker\Glue\WarehouseUsersBackendApi\Dependency\Facade\WarehouseUsersBackendApiToWarehouseUserFacadeInterface $warehouseUserFacade
     * @param \Spryker\Glue\WarehouseUsersBackendApi\Processor\Validator\WarehouseUserAssignmentValidatorInterface $warehouseUserAssignmentValidator
     * @param \Spryker\Glue\WarehouseUsersBackendApi\Processor\Mapper\WarehouseUserAssignmentMapperInterface $warehouseUserAssignmentMapper
     * @param \Spryker\Glue\WarehouseUsersBackendApi\Processor\Creator\ResponseCreatorInterface $responseCreator
     */
    public function __construct(
        WarehouseUsersBackendApiToWarehouseUserFacadeInterface $warehouseUserFacade,
        WarehouseUserAssignmentValidatorInterface $warehouseUserAssignmentValidator,
        WarehouseUserAssignmentMapperInterface $warehouseUserAssignmentMapper,
        ResponseCreatorInterface $responseCreator
    ) {
        $this->warehouseUserFacade = $warehouseUserFacade;
        $this->warehouseUserAssignmentValidator = $warehouseUserAssignmentValidator;
        $this->warehouseUserAssignmentMapper = $warehouseUserAssignmentMapper;
        $this->responseCreator = $responseCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiWarehouseUserAssignmentsAttributesTransfer $apiWarehouseUserAssignmentsAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function updateWarehouseUserAssignment(
        ApiWarehouseUserAssignmentsAttributesTransfer $apiWarehouseUserAssignmentsAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        $warehouseUserAssignmentTransfer = $this->findWarehouseUserAssignmentTransfer(
            $glueRequestTransfer->getResourceOrFail()->getIdOrFail(),
        );
        if (!$warehouseUserAssignmentTransfer) {
            return $this->responseCreator->createWarehouseUserAssignmentNotFoundErrorResponse();
        }

        if (
            !$this->warehouseUserAssignmentValidator->isCurrentUserAllowedToOperateWithWarehouseUserAssignment(
                $glueRequestTransfer,
                $warehouseUserAssignmentTransfer->getUserUuidOrFail(),
            )
        ) {
            return $this->responseCreator->createWarehouseUserAssignmentNotFoundErrorResponse();
        }

        $warehouseUserAssignmentTransfer = $this->warehouseUserAssignmentMapper
            ->mapApiWarehouseUserAssignmentsAttributesTransferToWarehouseUserAssignmentTransfer(
                $apiWarehouseUserAssignmentsAttributesTransfer,
                $warehouseUserAssignmentTransfer,
            );

        $warehouseUserAssignmentCollectionRequestTransfer = $this->createWarehouseUserAssignmentCollectionRequestTransfer(
            $warehouseUserAssignmentTransfer,
        );

        $warehouseUserAssignmentCollectionResponseTransfer = $this->warehouseUserFacade->updateWarehouseUserAssignmentCollection(
            $warehouseUserAssignmentCollectionRequestTransfer,
        );

        if ($warehouseUserAssignmentCollectionResponseTransfer->getErrors()->count() !== 0) {
            return $this->responseCreator->createWarehouseUserAssignmentErrorResponse(
                $warehouseUserAssignmentCollectionResponseTransfer->getErrors(),
            );
        }

        return $this->responseCreator->createWarehouseUserAssignmentResponse(
            $warehouseUserAssignmentCollectionResponseTransfer->getWarehouseUserAssignments(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionRequestTransfer
     */
    protected function createWarehouseUserAssignmentCollectionRequestTransfer(
        WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
    ): WarehouseUserAssignmentCollectionRequestTransfer {
        return (new WarehouseUserAssignmentCollectionRequestTransfer())
            ->addWarehouseUserAssignment($warehouseUserAssignmentTransfer);
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

        return $warehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments()->getIterator()->current();
    }
}
