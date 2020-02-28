<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStockGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\MerchantStockGui\Communication\Form\MerchantStockFormType;

class MerchantStockFormDataProvider
{
    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return array
     */
    public function getOptions(MerchantTransfer $merchantTransfer): array
    {
        return [
            MerchantStockFormType::STOCKS => $this->getData($merchantTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return array
     */
    public function getData(MerchantTransfer $merchantTransfer): array
    {
        return $merchantTransfer->getStockCollection()->getArrayCopy();
    }
}
