<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSearch;

use Generated\Shared\Transfer\MerchantCollectionTransfer;

interface MerchantSearchClientInterface
{
    /**
     * Specification:
     * - Makes Zed request.
     * - Returns the list of merchants with isActive=true.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function getMerchantCollection(): MerchantCollectionTransfer;

    /**
     * Specification:
     * - Makes ElasticSearch request.
     * - Returns the list of active merchants.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MerchantSearchCollectionTransfer
     */
    public function merchantSearch();
}
