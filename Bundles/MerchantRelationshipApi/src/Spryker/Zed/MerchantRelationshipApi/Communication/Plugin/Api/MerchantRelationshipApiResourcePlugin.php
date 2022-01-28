<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipApi\Communication\Plugin\Api;

use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Dependency\Plugin\ApiResourcePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantRelationshipApi\MerchantRelationshipApiConfig;

/**
 * @method \Spryker\Zed\MerchantRelationshipApi\Business\MerchantRelationshipApiFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantRelationshipApi\MerchantRelationshipApiConfig getConfig()
 */
class MerchantRelationshipApiResourcePlugin extends AbstractPlugin implements ApiResourcePluginInterface
{
    /**
     * {@inheritDoc}
     * - Creates a merchant relationship.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function add(ApiDataTransfer $apiDataTransfer): ApiItemTransfer
    {
         return $this->getFacade()->createMerchantRelationship($apiDataTransfer);
    }

    /**
     * {@inheritDoc}
     * - Returns a merchant relationship by id.
     *
     * @api
     *
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function get($id): ApiItemTransfer
    {
        return $this->getFacade()->getMerchantRelationship($id);
    }

    /**
     * {@inheritDoc}
     * - Updates a merchant relationship.
     *
     * @api
     *
     * @param int $id
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function update($id, ApiDataTransfer $apiDataTransfer): ApiItemTransfer
    {
        return $this->getFacade()->updateMerchantRelationship($id, $apiDataTransfer);
    }

    /**
     * {@inheritDoc}
     * - Removes a merchant relationship.
     *
     * @api
     *
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function remove($id): ApiItemTransfer
    {
        return $this->getFacade()->deleteMerchantRelationship($id);
    }

    /**
     * {@inheritDoc}
     * - Returns a collection of merchant relationships.
     * - Filters and paginates the collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function find(ApiRequestTransfer $apiRequestTransfer): ApiCollectionTransfer
    {
        return $this->getFacade()->getMerchantRelationshipCollection($apiRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return MerchantRelationshipApiConfig::RESOURCE_MERCHANT_RELATIONSHIP;
    }
}
