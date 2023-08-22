<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi\Processor\Creator;

use ArrayObject;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;
use Spryker\Glue\WarehouseUsersBackendApi\Processor\Mapper\WarehouseUserAssignmentMapperInterface;
use Spryker\Glue\WarehouseUsersBackendApi\WarehouseUsersBackendApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ResponseCreator implements ResponseCreatorInterface
{
    /**
     * @var \Spryker\Glue\WarehouseUsersBackendApi\Processor\Mapper\WarehouseUserAssignmentMapperInterface
     */
    protected WarehouseUserAssignmentMapperInterface $warehouseUserAssignmentMapper;

    /**
     * @var \Spryker\Glue\WarehouseUsersBackendApi\WarehouseUsersBackendApiConfig
     */
    protected WarehouseUsersBackendApiConfig $warehouseUsersBackendApiConfig;

    /**
     * @param \Spryker\Glue\WarehouseUsersBackendApi\Processor\Mapper\WarehouseUserAssignmentMapperInterface $warehouseUserAssignmentMapper
     * @param \Spryker\Glue\WarehouseUsersBackendApi\WarehouseUsersBackendApiConfig $warehouseUsersBackendApiConfig
     */
    public function __construct(
        WarehouseUserAssignmentMapperInterface $warehouseUserAssignmentMapper,
        WarehouseUsersBackendApiConfig $warehouseUsersBackendApiConfig
    ) {
        $this->warehouseUserAssignmentMapper = $warehouseUserAssignmentMapper;
        $this->warehouseUsersBackendApiConfig = $warehouseUsersBackendApiConfig;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createWarehouseUserAssignmentResponse(
        ArrayObject $warehouseUserAssignmentTransfers,
        ?PaginationTransfer $paginationTransfer = null
    ): GlueResponseTransfer {
        $glueResponseTransfer = new GlueResponseTransfer();
        foreach ($warehouseUserAssignmentTransfers as $warehouseUserAssignmentTransfer) {
            $glueResponseTransfer->addResource(
                $this->createWarehouseUserAssignmentsResourceTransfer($warehouseUserAssignmentTransfer),
            );
        }

        if ($paginationTransfer) {
            $glueResponseTransfer->setPagination($paginationTransfer);
        }

        return $glueResponseTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createWarehouseUserAssignmentErrorResponse(ArrayObject $errorTransfers): GlueResponseTransfer
    {
        $glueResponseTransfer = (new GlueResponseTransfer())->setHttpStatus(Response::HTTP_BAD_REQUEST);
        $validationGlossaryKeyToRestErrorMapping = $this->warehouseUsersBackendApiConfig->getValidationGlossaryKeyToRestErrorMapping();
        foreach ($errorTransfers as $errorTransfer) {
            if (isset($validationGlossaryKeyToRestErrorMapping[$errorTransfer->getMessageOrFail()])) {
                $glueResponseTransfer->addError(
                    (new GlueErrorTransfer())->fromArray(
                        $validationGlossaryKeyToRestErrorMapping[$errorTransfer->getMessageOrFail()],
                        true,
                    ),
                );

                continue;
            }

            $glueResponseTransfer->addError($this->createGlueErrorTransfer(
                $errorTransfer->getMessageOrFail(),
                Response::HTTP_BAD_REQUEST,
                '',
            ));
        }

        return $glueResponseTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createWarehouseUserAssignmentNotFoundErrorResponse(): GlueResponseTransfer
    {
        return (new GlueResponseTransfer())
            ->setHttpStatus(Response::HTTP_NOT_FOUND)
            ->addError($this->createGlueErrorTransfer(
                WarehouseUsersBackendApiConfig::RESPONSE_DETAILS_WAREHOUSE_USER_ASSIGNMENT_NOT_FOUND,
                Response::HTTP_NOT_FOUND,
                WarehouseUsersBackendApiConfig::RESPONSE_CODE_WAREHOUSE_USER_ASSIGNMENT_NOT_FOUND,
            ));
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createWarehouseUserAssignmentForbiddenErrorResponse(): GlueResponseTransfer
    {
        return (new GlueResponseTransfer())
            ->setHttpStatus(Response::HTTP_FORBIDDEN)
            ->addError($this->createGlueErrorTransfer(
                WarehouseUsersBackendApiConfig::RESPONSE_DETAILS_OPERATION_IS_FORBIDDEN,
                Response::HTTP_FORBIDDEN,
                WarehouseUsersBackendApiConfig::RESPONSE_CODE_OPERATION_IS_FORBIDDEN,
            ));
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createNoContentResponse(): GlueResponseTransfer
    {
        return (new GlueResponseTransfer())->setHttpStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function createWarehouseUserAssignmentsResourceTransfer(
        WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
    ): GlueResourceTransfer {
        $warehouseUserAssignmentsBackendApiAttributesTransfer = $this->warehouseUserAssignmentMapper
            ->mapWarehouseUserAssignmentTransferToWarehouseUserAssignmentsBackendApiAttributesTransfer(
                $warehouseUserAssignmentTransfer,
                new WarehouseUserAssignmentsBackendApiAttributesTransfer(),
            );

        return (new GlueResourceTransfer())
            ->setId($warehouseUserAssignmentTransfer->getUuidOrFail())
            ->setType(WarehouseUsersBackendApiConfig::RESOURCE_TYPE_WAREHOUSE_USER_ASSIGNMENTS)
            ->setAttributes($warehouseUserAssignmentsBackendApiAttributesTransfer);
    }

    /**
     * @param string $message
     * @param int $status
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\GlueErrorTransfer
     */
    protected function createGlueErrorTransfer(string $message, int $status, string $code): GlueErrorTransfer
    {
        return (new GlueErrorTransfer())
            ->setMessage($message)
            ->setStatus($status)
            ->setCode($code);
    }
}
