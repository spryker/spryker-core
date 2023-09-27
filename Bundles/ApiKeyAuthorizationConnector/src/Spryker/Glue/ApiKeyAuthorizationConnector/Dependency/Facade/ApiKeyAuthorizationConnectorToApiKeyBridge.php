<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ApiKeyAuthorizationConnector\Dependency\Facade;

use Generated\Shared\Transfer\ApiKeyCollectionTransfer;
use Generated\Shared\Transfer\ApiKeyCriteriaTransfer;
use Spryker\Zed\ApiKey\Business\ApiKeyFacadeInterface;

class ApiKeyAuthorizationConnectorToApiKeyBridge implements ApiKeyAuthorizationConnectorToApiKeyInterface
{
    /**
     * @var \Spryker\Zed\ApiKey\Business\ApiKeyFacadeInterface
     */
    protected ApiKeyFacadeInterface $apiKeyFacade;

    /**
     * @param \Spryker\Zed\ApiKey\Business\ApiKeyFacadeInterface $apiKeyFacade
     */
    public function __construct($apiKeyFacade)
    {
        $this->apiKeyFacade = $apiKeyFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiKeyCriteriaTransfer $apiKeyCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyCollectionTransfer
     */
    public function getApiKeyCollection(ApiKeyCriteriaTransfer $apiKeyCriteriaTransfer): ApiKeyCollectionTransfer
    {
        return $this->apiKeyFacade->getApiKeyCollection($apiKeyCriteriaTransfer);
    }
}
