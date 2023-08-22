<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKey\Business;

use Generated\Shared\Transfer\ApiKeyCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\ApiKeyCollectionRequestTransfer;
use Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer;
use Generated\Shared\Transfer\ApiKeyCollectionTransfer;
use Generated\Shared\Transfer\ApiKeyCriteriaTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ApiKey\Business\ApiKeyBusinessFactory getFactory()
 * @method \Spryker\Zed\ApiKey\Persistence\ApiKeyRepositoryInterface getRepository()
 * @method \Spryker\Zed\ApiKey\Persistence\ApiKeyEntityManagerInterface getEntityManager()
 */
class ApiKeyFacade extends AbstractFacade implements ApiKeyFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiKeyCriteriaTransfer $apiKeyCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyCollectionTransfer
     */
    public function getApiKeyCollection(ApiKeyCriteriaTransfer $apiKeyCriteriaTransfer): ApiKeyCollectionTransfer
    {
        return $this->getRepository()->getApiKeyCollection($apiKeyCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiKeyCollectionRequestTransfer $apiKeyCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer
     */
    public function createApiKeyCollection(ApiKeyCollectionRequestTransfer $apiKeyCollectionRequestTransfer): ApiKeyCollectionResponseTransfer
    {
        return $this->getFactory()->createApiKeyCreator()->create($apiKeyCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiKeyCollectionRequestTransfer $apiKeyCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer
     */
    public function updateApiKeyCollection(ApiKeyCollectionRequestTransfer $apiKeyCollectionRequestTransfer): ApiKeyCollectionResponseTransfer
    {
        return $this->getFactory()->createApiKeyUpdater()->update($apiKeyCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiKeyCollectionDeleteCriteriaTransfer $apiKeyCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer
     */
    public function deleteApiKeyCollection(ApiKeyCollectionDeleteCriteriaTransfer $apiKeyCollectionDeleteCriteriaTransfer): ApiKeyCollectionResponseTransfer
    {
        return $this->getFactory()->createApiKeyDeleter()->delete($apiKeyCollectionDeleteCriteriaTransfer);
    }
}
