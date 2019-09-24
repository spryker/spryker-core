<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantProfileTransfer;
use Spryker\Zed\MerchantProfileGui\Communication\Form\MerchantProfileFormType;
use Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig;

class MerchantProfileFormDataProvider
{
    /**
     * @var \Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig $config
     */
    public function __construct(MerchantProfileGuiConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'data_class' => MerchantProfileTransfer::class,
            'label' => false,
            MerchantProfileFormType::SALUTATION_CHOICES_OPTION => $this->config->getSalutationChoices(),
        ];
    }
}
