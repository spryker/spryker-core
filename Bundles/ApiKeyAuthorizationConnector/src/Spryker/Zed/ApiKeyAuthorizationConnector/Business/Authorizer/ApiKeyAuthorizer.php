<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKeyAuthorizationConnector\Business\Authorizer;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\AuthorizationResponseTransfer;
use Spryker\Zed\ApiKeyAuthorizationConnector\ApiKeyAuthorizationConnectorConfig;
use Spryker\Zed\ApiKeyAuthorizationConnector\Business\Logger\ApiKeyAuthorizationLoggerInterface;

class ApiKeyAuthorizer implements ApiKeyAuthorizerInterface
{
    /**
     * @var \Spryker\Zed\ApiKeyAuthorizationConnector\ApiKeyAuthorizationConnectorConfig
     */
    protected ApiKeyAuthorizationConnectorConfig $config;

    /**
     * @var \Spryker\Zed\ApiKeyAuthorizationConnector\Business\Logger\ApiKeyAuthorizationLoggerInterface
     */
    protected ApiKeyAuthorizationLoggerInterface $logger;

    /**
     * @param \Spryker\Zed\ApiKeyAuthorizationConnector\ApiKeyAuthorizationConnectorConfig $config
     * @param \Spryker\Zed\ApiKeyAuthorizationConnector\Business\Logger\ApiKeyAuthorizationLoggerInterface $logger
     */
    public function __construct(
        ApiKeyAuthorizationConnectorConfig $config,
        ApiKeyAuthorizationLoggerInterface $logger
    ) {
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AuthorizationResponseTransfer
     */
    public function authorize(AuthorizationRequestTransfer $authorizationRequestTransfer): AuthorizationResponseTransfer
    {
        $authorizationResponsetTransfer = (new AuthorizationResponseTransfer())->setIsAuthorized(false);

        $apiKeyIdentifier = $authorizationRequestTransfer->getIdentityOrFail()->getApiKeyIdentifier();
        if ($apiKeyIdentifier === null) {
            return $authorizationResponsetTransfer;
        }

        $this->logAppliedKeyName($authorizationRequestTransfer, $apiKeyIdentifier);

        return $authorizationResponsetTransfer->setIsAuthorized(true);
    }

    /**
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     * @param string $apiKeyIdentifier
     *
     * @return void
     */
    protected function logAppliedKeyName(AuthorizationRequestTransfer $authorizationRequestTransfer, string $apiKeyIdentifier): void
    {
        if (!$this->config->isLoggingEnabled()) {
            return;
        }

        $this->logger->logInfo($authorizationRequestTransfer, $apiKeyIdentifier);
    }
}
