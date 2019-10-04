<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProfileStorage\Mapper;

use Generated\Shared\Transfer\MerchantProfileTransfer;

class MerchantProfileStorageMapper implements MerchantProfileStorageMapperInterface
{
    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function mapMerchantProfileStorageDataToMerchantProfileTransfer(array $data): MerchantProfileTransfer
    {
        $merchantTransfer = new MerchantProfileTransfer();
        $merchantTransfer->fromArray($data, true);

        return $merchantTransfer;
    }
}
