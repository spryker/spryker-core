<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProfileStorage;

use Generated\Shared\Transfer\MerchantProfileViewTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\MerchantProfileStorage\MerchantProfileStorageFactory getFactory()
 */
class MerchantProfileStorageClient extends AbstractClient implements MerchantProfileStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\MerchantProfileViewTransfer
     */
    public function mapMerchantProfileStorageViewData(array $data): MerchantProfileViewTransfer
    {
        return $this->getFactory()
            ->createMerchantProfileStorageMapper()
            ->mapMerchantProfileStorageDataToMerchantProfileViewTransfer($data);
    }
}
