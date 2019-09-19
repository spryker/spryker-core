<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\MerchantGui\MerchantGuiConfig;

class MerchantFormDataProvider
{
    /**
     * @var \Spryker\Zed\MerchantGui\MerchantGuiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\MerchantGui\MerchantGuiConfig $config
     */
    public function __construct(MerchantGuiConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function getData(): MerchantTransfer
    {
        return new MerchantTransfer();
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'data_class' => MerchantTransfer::class,
        ];
    }
}
