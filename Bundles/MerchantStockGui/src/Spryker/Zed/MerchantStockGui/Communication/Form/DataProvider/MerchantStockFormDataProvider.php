<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStockGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantTransfer;

class MerchantStockFormDataProvider
{
    /**
     * @uses \Spryker\Zed\MerchantStockGui\Communication\Form\MerchantStockFormType::FIELD_STOCKS
     *
     * @var string
     */
    protected const STOCKS = 'stocks';

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return array<string, mixed>
     */
    public function getOptions(MerchantTransfer $merchantTransfer): array
    {
        return [
            static::STOCKS => $this->getData($merchantTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return array<\Generated\Shared\Transfer\StockTransfer>
     */
    public function getData(MerchantTransfer $merchantTransfer): array
    {
        return $merchantTransfer->getStocks()->getArrayCopy();
    }
}
