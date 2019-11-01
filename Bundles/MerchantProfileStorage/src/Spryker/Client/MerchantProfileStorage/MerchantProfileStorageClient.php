<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProfileStorage;

use Generated\Shared\Transfer\MerchantProfileStorageTransfer;
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
     * @return \Generated\Shared\Transfer\MerchantProfileStorageTransfer
     */
    public function mapMerchantProfileStorageData(array $data): MerchantProfileStorageTransfer
    {
        return $this->getFactory()
            ->createMerchantProfileStorageMapper()
            ->mapMerchantProfileStorageDataToMerchantProfileStorageTransfer($data);
    }
}
