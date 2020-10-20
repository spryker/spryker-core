<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Dependency\Client;

use ArrayObject;
use Generated\Shared\Transfer\MerchantSearchCollectionTransfer;
use Generated\Shared\Transfer\MerchantSearchRequestTransfer;
use Generated\Shared\Transfer\MerchantSearchTransfer;

class MerchantsRestApiToMerchantSearchClientBridge implements MerchantsRestApiToMerchantSearchClientInterface
{
    /**
     * @todo

     * @param \Generated\Shared\Transfer\MerchantSearchRequestTransfer $merchantSearchRequestTransfer
     *
     * @return array
     */
    public function searchMerchants(MerchantSearchRequestTransfer $merchantSearchRequestTransfer): array
    {
        return [
            'MercahantCollection' => (new MerchantSearchCollectionTransfer())
                ->setNbResults(1)
                ->setMerchants(new ArrayObject([
                    (new MerchantSearchTransfer())
                        ->setName('Spryker'),
                    (new MerchantSearchTransfer())
                        ->setName('Video King'),
                ])),
        ];
    }
}
