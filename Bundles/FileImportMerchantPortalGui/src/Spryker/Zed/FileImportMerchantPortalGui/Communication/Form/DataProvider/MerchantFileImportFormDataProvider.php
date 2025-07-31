<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantFileImportTransfer;
use Spryker\Zed\FileImportMerchantPortalGui\FileImportMerchantPortalGuiConfig;

class MerchantFileImportFormDataProvider implements MerchantFileImportFormDataProviderInterface
{
    /**
     * @var string
     */
    public const OPTION_TYPE_CHOICES = 'option_type_choices';

    /**
     * @param \Spryker\Zed\FileImportMerchantPortalGui\FileImportMerchantPortalGuiConfig $config
     */
    public function __construct(
        protected FileImportMerchantPortalGuiConfig $config
    ) {
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantFileImportTransfer
     */
    public function getData(): MerchantFileImportTransfer
    {
        return new MerchantFileImportTransfer();
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            static::OPTION_TYPE_CHOICES => array_combine(
                $this->config->getImportTypes(),
                $this->config->getImportTypes(),
            ),
        ];
    }
}
