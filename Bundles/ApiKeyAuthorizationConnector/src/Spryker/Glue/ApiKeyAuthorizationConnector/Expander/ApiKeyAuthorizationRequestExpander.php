<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ApiKeyAuthorizationConnector\Expander;

use DateTime;
use Generated\Shared\Transfer\ApiKeyConditionsTransfer;
use Generated\Shared\Transfer\ApiKeyCriteriaTransfer;
use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\CriteriaRangeFilterTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\ApiKeyAuthorizationConnector\ApiKeyAuthorizationConnectorConfig;
use Spryker\Glue\ApiKeyAuthorizationConnector\Dependency\Facade\ApiKeyAuthorizationConnectorToApiKeyInterface;

class ApiKeyAuthorizationRequestExpander implements ApiKeyAuthorizationRequestExpanderInterface
{
    /**
     * @var string
     */
    protected const FILTER_TO_DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var \Spryker\Glue\ApiKeyAuthorizationConnector\Dependency\Facade\ApiKeyAuthorizationConnectorToApiKeyInterface
     */
    protected ApiKeyAuthorizationConnectorToApiKeyInterface $apiKeyFacade;

    /**
     * @var \Spryker\Glue\ApiKeyAuthorizationConnector\ApiKeyAuthorizationConnectorConfig
     */
    protected ApiKeyAuthorizationConnectorConfig $config;

    /**
     * @param \Spryker\Glue\ApiKeyAuthorizationConnector\Dependency\Facade\ApiKeyAuthorizationConnectorToApiKeyInterface $apiKeyFacade
     * @param \Spryker\Glue\ApiKeyAuthorizationConnector\ApiKeyAuthorizationConnectorConfig $config
     */
    public function __construct(
        ApiKeyAuthorizationConnectorToApiKeyInterface $apiKeyFacade,
        ApiKeyAuthorizationConnectorConfig $config
    ) {
        $this->apiKeyFacade = $apiKeyFacade;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AuthorizationRequestTransfer
     */
    public function expand(
        AuthorizationRequestTransfer $authorizationRequestTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): AuthorizationRequestTransfer {
        $apiKey = $this->getApiKeyFromRequest($glueRequestTransfer);

        if ($apiKey === null) {
            return $authorizationRequestTransfer;
        }

        $apiKeyCriteriaTransfer = $this->getApiKeyCriteria($apiKey);
        $apiKeyCollectionTransfer = $this->apiKeyFacade->getApiKeyCollection($apiKeyCriteriaTransfer);

        $apiKeyTransfersArray = $apiKeyCollectionTransfer->getApiKeys();
        if ($apiKeyTransfersArray->count() === 0) {
            return $authorizationRequestTransfer;
        }

        $usedApiKeyName = $apiKeyTransfersArray->offsetGet(0)->getNameOrFail();
        $identity = $authorizationRequestTransfer->getIdentityOrFail();
        $identity->setApiKeyIdentifier($usedApiKeyName);

        return $authorizationRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return string|null
     */
    protected function getApiKeyFromRequest(GlueRequestTransfer $glueRequestTransfer): ?string
    {
        $headers = $glueRequestTransfer->getMeta();
        $queryParams = $glueRequestTransfer->getQueryFields();

        $apiKeyHeader = $this->config->getApiKeyRequestHeaderName();
        if (isset($headers[$apiKeyHeader])) {
            return $headers[$apiKeyHeader][0] ?? null;
        }

        $apiKeyRequestParam = $this->config->getApiKeyRequestParamName();
        if (isset($queryParams[$apiKeyRequestParam])) {
            return $queryParams[$apiKeyRequestParam];
        }

        return null;
    }

    /**
     * @param string $apiKey
     *
     * @return \Generated\Shared\Transfer\ApiKeyCriteriaTransfer
     */
    protected function getApiKeyCriteria(string $apiKey): ApiKeyCriteriaTransfer
    {
        $criteriaRangeFilterTransfer = (new CriteriaRangeFilterTransfer())
            ->setFrom((new DateTime())->format(static::FILTER_TO_DATE_TIME_FORMAT));

        $apiKeyConditionsTransfer = (new ApiKeyConditionsTransfer())
            ->addApiKey($apiKey)
            ->setFilterValidTo($criteriaRangeFilterTransfer);

        return (new ApiKeyCriteriaTransfer())
            ->setApiKeyConditions($apiKeyConditionsTransfer);
    }
}
