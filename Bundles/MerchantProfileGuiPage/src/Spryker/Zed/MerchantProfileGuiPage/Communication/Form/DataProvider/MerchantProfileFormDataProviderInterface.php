<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantProfileTransfer;

interface MerchantProfileFormDataProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer|null $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function getData(?MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer;

    /**
     * @return array
     */
    public function getOptions(): array;
}
