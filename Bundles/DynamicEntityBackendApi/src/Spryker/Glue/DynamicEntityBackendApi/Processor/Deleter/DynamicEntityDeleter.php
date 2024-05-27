<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Processor\Deleter;

use Exception;
use Generated\Shared\Transfer\DynamicEntityCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToDynamicEntityFacadeInterface;
use Spryker\Glue\DynamicEntityBackendApi\Logger\DynamicEntityBackendApiLoggerInterface;
use Spryker\Glue\DynamicEntityBackendApi\Mapper\GlueRequestDynamicEntityMapper;
use Spryker\Glue\DynamicEntityBackendApi\Mapper\GlueResponseDynamicEntityMapper;

class DynamicEntityDeleter implements DynamicEntityDeleterInterface
{
    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToDynamicEntityFacadeInterface
     */
    protected DynamicEntityBackendApiToDynamicEntityFacadeInterface $dynamicEntityFacade;

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\Mapper\GlueRequestDynamicEntityMapper
     */
    protected GlueRequestDynamicEntityMapper $requestMapper;

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\Mapper\GlueResponseDynamicEntityMapper
     */
    protected GlueResponseDynamicEntityMapper $responseMapper;

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\Logger\DynamicEntityBackendApiLoggerInterface
     */
    protected DynamicEntityBackendApiLoggerInterface $logger;

    /**
     * @param \Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToDynamicEntityFacadeInterface $dynamicEntityFacade
     * @param \Spryker\Glue\DynamicEntityBackendApi\Mapper\GlueRequestDynamicEntityMapper $requestMapper
     * @param \Spryker\Glue\DynamicEntityBackendApi\Mapper\GlueResponseDynamicEntityMapper $responseMapper
     * @param \Spryker\Glue\DynamicEntityBackendApi\Logger\DynamicEntityBackendApiLoggerInterface $logger
     */
    public function __construct(
        DynamicEntityBackendApiToDynamicEntityFacadeInterface $dynamicEntityFacade,
        GlueRequestDynamicEntityMapper $requestMapper,
        GlueResponseDynamicEntityMapper $responseMapper,
        DynamicEntityBackendApiLoggerInterface $logger
    ) {
        $this->dynamicEntityFacade = $dynamicEntityFacade;
        $this->requestMapper = $requestMapper;
        $this->responseMapper = $responseMapper;
        $this->logger = $logger;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function deleteDynamicEntity(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $this->logger->logInfo($glueRequestTransfer);

        $dynamicEntityCollectionDeleteCriteriaTransfer = $this->requestMapper->mapGlueRequestTransferToDynamicEntityCollectionDeleteCriteriaTransfer($glueRequestTransfer, new DynamicEntityCollectionDeleteCriteriaTransfer());

        try {
            $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->deleteDynamicEntityCollection($dynamicEntityCollectionDeleteCriteriaTransfer);
        } catch (Exception $e) {
            $this->logger->logError($glueRequestTransfer, $e);

            throw $e;
        }

        return $this->responseMapper->mapDynamicEntityCollectionResponseTransferToGlueResponseTransfer(
            $dynamicEntityCollectionResponseTransfer,
            (new DynamicEntityCollectionRequestTransfer())->setTableAlias($dynamicEntityCollectionDeleteCriteriaTransfer->getDynamicEntityConditionsOrFail()->getTableAliasOrFail()),
            $glueRequestTransfer,
        );
    }
}
