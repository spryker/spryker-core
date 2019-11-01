<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageFactory getFactory()
 */
class MerchantProductOfferStorageClient extends AbstractClient implements MerchantProductOfferStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer[]
     */
    public function getProductOfferStorageCollection(string $concreteSku): array
    {
        return $this->getFactory()
            ->createProductOfferStorageReader()
            ->getProductOfferStorageCollection($concreteSku);
    }
}
