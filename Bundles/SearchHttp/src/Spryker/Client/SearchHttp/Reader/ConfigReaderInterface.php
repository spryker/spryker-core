<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Reader;

use Generated\Shared\Transfer\SearchHttpConfigCollectionTransfer;

interface ConfigReaderInterface
{
    /**
     * @return \Generated\Shared\Transfer\SearchHttpConfigCollectionTransfer
     */
    public function getSearchHttpConfigCollectionForCurrentStore(): SearchHttpConfigCollectionTransfer;
}
