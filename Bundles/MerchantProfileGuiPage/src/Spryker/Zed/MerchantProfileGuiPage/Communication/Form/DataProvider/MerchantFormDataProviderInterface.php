<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantTransfer;

interface MerchantFormDataProviderInterface
{
    /**
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function getData(int $idMerchant): ?MerchantTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return array
     */
    public function getOptions(MerchantTransfer $merchantTransfer): array;
}
