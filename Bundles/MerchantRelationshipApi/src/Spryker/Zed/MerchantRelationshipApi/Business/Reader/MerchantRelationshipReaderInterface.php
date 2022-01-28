<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipApi\Business\Reader;

use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;

interface MerchantRelationshipReaderInterface
{
    /**
     * @param int $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function getMerchantRelationship(int $idMerchantRelationship): ApiItemTransfer;

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function getMerchantRelationshipCollection(ApiRequestTransfer $apiRequestTransfer): ApiCollectionTransfer;
}
