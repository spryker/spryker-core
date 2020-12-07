<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSearch\Reader;

use Generated\Shared\Transfer\MerchantSearchRequestTransfer;

interface MerchantSearchReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantSearchRequestTransfer $merchantSearchRequestTransfer
     *
     * @return mixed
     */
    public function search(MerchantSearchRequestTransfer $merchantSearchRequestTransfer);
}
