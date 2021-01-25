<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStockGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantStockGui\Communication\Form\DataProvider\MerchantStockFormDataProvider;
use Spryker\Zed\MerchantStockGui\Communication\Form\MerchantStockFormType;

/**
 * @method \Spryker\Zed\MerchantStockGui\MerchantStockGuiConfig getConfig()
 */
class MerchantStockGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantStockGui\Communication\Form\MerchantStockFormType
     */
    public function createMerchantStockForm(): MerchantStockFormType
    {
        return new MerchantStockFormType();
    }

    /**
     * @return \Spryker\Zed\MerchantStockGui\Communication\Form\DataProvider\MerchantStockFormDataProvider
     */
    public function createMerchantStockFormDataProvider(): MerchantStockFormDataProvider
    {
        return new MerchantStockFormDataProvider();
    }
}
