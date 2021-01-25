<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\MerchantSearchCollectionTransfer;
use Generated\Shared\Transfer\MerchantSearchRequestTransfer;
use Generated\Shared\Transfer\MerchantStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface MerchantRestResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer[] $merchantStorageTransfers
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function createMerchantRestResources(array $merchantStorageTransfers, string $localeName): array;

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createMerchantsRestResponse(
        MerchantStorageTransfer $merchantStorageTransfer,
        string $localeName
    ): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createMerchantNotFoundErrorResponse(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createMerchantIdentifierMissingErrorResponse(): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\MerchantSearchRequestTransfer $merchantSearchRequestTransfer
     * @param \Generated\Shared\Transfer\MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer[] $merchantStorageTransfers
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createMerchantListRestResponse(
        MerchantSearchRequestTransfer $merchantSearchRequestTransfer,
        MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer,
        array $merchantStorageTransfers,
        string $localeName
    ): RestResponseInterface;
}
