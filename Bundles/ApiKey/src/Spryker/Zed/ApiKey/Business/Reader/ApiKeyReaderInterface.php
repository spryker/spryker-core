<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKey\Business\Reader;

use Generated\Shared\Transfer\ApiKeyCollectionTransfer;
use Generated\Shared\Transfer\ApiKeyCriteriaTransfer;

interface ApiKeyReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiKeyCriteriaTransfer $apiKeyCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyCollectionTransfer
     */
    public function getApiKeyCollection(ApiKeyCriteriaTransfer $apiKeyCriteriaTransfer): ApiKeyCollectionTransfer;
}
