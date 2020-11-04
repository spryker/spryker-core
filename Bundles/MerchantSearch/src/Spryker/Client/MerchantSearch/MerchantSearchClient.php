<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSearch;

use Generated\Shared\Transfer\MerchantSearchRequestTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\MerchantSearch\MerchantSearchFactory getFactory()
 */
class MerchantSearchClient extends AbstractClient implements MerchantSearchClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantSearchRequestTransfer $merchantSearchRequestTransfer
     *
     * @return mixed
     */
    public function search(MerchantSearchRequestTransfer $merchantSearchRequestTransfer)
    {
        return $this->getFactory()
            ->createMerchantSearchReader()
            ->search($merchantSearchRequestTransfer);
    }
}
