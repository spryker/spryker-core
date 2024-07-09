<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseOauthBackendApi\Processor\Creator;

use Generated\Shared\Transfer\GlueAuthenticationRequestContextTransfer;
use Generated\Shared\Transfer\GlueAuthenticationRequestTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Spryker\Glue\WarehouseOauthBackendApi\Dependency\Facade\WarehouseOauthBackendApiToAuthenticationFacadeInterface;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\Logger\AuditLoggerInterface;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\Reader\WarehouseUserAssignmentReaderInterface;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\ResponseBuilder\WarehouseResponseBuilderInterface;
use Spryker\Glue\WarehouseOauthBackendApi\WarehouseOauthBackendApiConfig;

class WarehouseTokenCreator implements WarehouseTokenCreatorInterface
{
    /**
     * @uses \Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\ApplicationIdentifierRequestBuilderPlugin::GLUE_BACKEND_API_APPLICATION
     *
     * @var string
     */
    protected const GLUE_BACKEND_API_APPLICATION = 'GLUE_BACKEND_API_APPLICATION';

    /**
     * @var \Spryker\Glue\WarehouseOauthBackendApi\Processor\Reader\WarehouseUserAssignmentReaderInterface
     */
    protected WarehouseUserAssignmentReaderInterface $warehouseUserAssignmentReader;

    /**
     * @var \Spryker\Glue\WarehouseOauthBackendApi\Dependency\Facade\WarehouseOauthBackendApiToAuthenticationFacadeInterface
     */
    protected WarehouseOauthBackendApiToAuthenticationFacadeInterface $authenticationFacade;

    /**
     * @var \Spryker\Glue\WarehouseOauthBackendApi\Processor\ResponseBuilder\WarehouseResponseBuilderInterface
     */
    protected WarehouseResponseBuilderInterface $warehouseResponseBuilder;

    /**
     * @var \Spryker\Glue\WarehouseOauthBackendApi\Processor\Logger\AuditLoggerInterface
     */
    protected AuditLoggerInterface $auditLogger;

    /**
     * @param \Spryker\Glue\WarehouseOauthBackendApi\Processor\Reader\WarehouseUserAssignmentReaderInterface $warehouseUserAssignmentReader
     * @param \Spryker\Glue\WarehouseOauthBackendApi\Dependency\Facade\WarehouseOauthBackendApiToAuthenticationFacadeInterface $authenticationFacade
     * @param \Spryker\Glue\WarehouseOauthBackendApi\Processor\ResponseBuilder\WarehouseResponseBuilderInterface $warehouseResponseBuilder
     * @param \Spryker\Glue\WarehouseOauthBackendApi\Processor\Logger\AuditLoggerInterface $auditLogger
     */
    public function __construct(
        WarehouseUserAssignmentReaderInterface $warehouseUserAssignmentReader,
        WarehouseOauthBackendApiToAuthenticationFacadeInterface $authenticationFacade,
        WarehouseResponseBuilderInterface $warehouseResponseBuilder,
        AuditLoggerInterface $auditLogger
    ) {
        $this->warehouseUserAssignmentReader = $warehouseUserAssignmentReader;
        $this->authenticationFacade = $authenticationFacade;
        $this->warehouseResponseBuilder = $warehouseResponseBuilder;
        $this->auditLogger = $auditLogger;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createWarehouseToken(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $warehouseUserAssignmentTransfer = $this->warehouseUserAssignmentReader->findActiveWarehouseUserAssignment($glueRequestTransfer);

        if (!$warehouseUserAssignmentTransfer) {
            $this->auditLogger->addWarehouseUserFailedLoginAuditLog($glueRequestTransfer);

            return $this->warehouseResponseBuilder->createForbiddenErrorResponse();
        }

        $oauthRequestTransfer = (new OauthRequestTransfer())
            ->setGrantType(WarehouseOauthBackendApiConfig::WAREHOUSE_GRANT_TYPE)
            ->setIdWarehouse($warehouseUserAssignmentTransfer->getWarehouseOrFail()->getIdStockOrFail());

        $glueAuthenticationRequestContextTransfer = (new GlueAuthenticationRequestContextTransfer())
            ->setRequestApplication(static::GLUE_BACKEND_API_APPLICATION);

        $glueAuthenticationRequestTransfer = (new GlueAuthenticationRequestTransfer())
            ->setOauthRequest($oauthRequestTransfer)
            ->setRequestContext($glueAuthenticationRequestContextTransfer);

        $glueAuthenticationResponseTransfer = $this->authenticationFacade->authenticate($glueAuthenticationRequestTransfer);
        $oauthResponseTransfer = $glueAuthenticationResponseTransfer->getOauthResponseOrFail();

        if (!$oauthResponseTransfer->getIsValid()) {
            $this->auditLogger->addWarehouseUserFailedLoginAuditLog($glueRequestTransfer);

            return $this->warehouseResponseBuilder->createOauthBadRequestErrorResponse($oauthResponseTransfer->getErrorOrFail());
        }

        $this->auditLogger->addWarehouseUserSuccessfulLoginAuditLog($glueRequestTransfer);

        return $this->warehouseResponseBuilder->createWarehouseTokenResponse($oauthResponseTransfer);
    }
}
