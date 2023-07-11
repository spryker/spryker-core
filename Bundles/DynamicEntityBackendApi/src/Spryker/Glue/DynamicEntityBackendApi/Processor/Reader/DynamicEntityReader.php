<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Processor\Reader;

use Exception;
use Generated\Shared\Transfer\DynamicEntityConfigurationConditionsTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToDynamicEntityFacadeInterface;
use Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig;
use Spryker\Glue\DynamicEntityBackendApi\Logger\DynamicEntityBackendApiLoggerInterface;
use Spryker\Glue\DynamicEntityBackendApi\Mapper\GlueRequestDynamicEntityMapper;
use Spryker\Glue\DynamicEntityBackendApi\Mapper\GlueResponseDynamicEntityMapper;

class DynamicEntityReader implements DynamicEntityReaderInterface
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
    public function getDynamicEntityCollection(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $this->logger->logInfo($glueRequestTransfer);

        $dynamicEntityCriteriaTransfer = $this->requestMapper->mapGlueRequestToDynamicEntityCriteriaTransfer($glueRequestTransfer);

        try {
            $dynamicEntityCollectionTransfer = $this->dynamicEntityFacade->getDynamicEntityCollection($dynamicEntityCriteriaTransfer);
        } catch (Exception $e) {
            $this->logger->logError($glueRequestTransfer, $e);

            throw $e;
        }

        return $this->responseMapper->mapDynamicEntityCollectionTransferToGlueResponseTransfer($dynamicEntityCollectionTransfer);
    }

    /**
     * @param string $id
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getDynamicEntity(
        string $id,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        $this->logger->logInfo($glueRequestTransfer);

        $dynamicEntityCriteriaTransfer = $this->requestMapper->mapGlueRequestToDynamicEntityCriteriaTransfer($glueRequestTransfer, $id);

        try {
            $dynamicEntityCollectionTransfer = $this->dynamicEntityFacade->getDynamicEntityCollection($dynamicEntityCriteriaTransfer);
        } catch (Exception $e) {
            $this->logger->logError($glueRequestTransfer, $e);

            throw $e;
        }

        if ($dynamicEntityCollectionTransfer->getDynamicEntities()->count() === 0) {
            return $this->responseMapper->mapErrorToResponseTransfer(DynamicEntityBackendApiConfig::GLOSSARY_KEY_ERROR_ENTITY_DOES_NOT_EXIST, new GlueResponseTransfer());
        }

        return $this->responseMapper->mapDynamicEntityCollectionTransferToGlueResponseTransfer($dynamicEntityCollectionTransfer);
    }

    /**
     * @return array<\Generated\Shared\Transfer\DynamicEntityConfigurationTransfer>
     */
    public function getDynamicEntityConfigurations(): array
    {
        $dynamicEntityConfigurationCollectionTransfer = $this->dynamicEntityFacade->getDynamicEntityConfigurationCollection(
            $this->createDynamicEntityConfigurationCriteriaTransfer(),
        );

        return $dynamicEntityConfigurationCollectionTransfer->getDynamicEntityConfigurations()->getArrayCopy();
    }

    /**
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer
     */
    protected function createDynamicEntityConfigurationCriteriaTransfer(): DynamicEntityConfigurationCriteriaTransfer
    {
        $dynamicEntityConfigurationCriteriaTransfer = new DynamicEntityConfigurationCriteriaTransfer();
        $dynamicEntityConfigurationCriteriaTransfer->setDynamicEntityConfigurationConditions(
            (new DynamicEntityConfigurationConditionsTransfer())
                ->setIsActive(true),
        );

        return $dynamicEntityConfigurationCriteriaTransfer;
    }
}
