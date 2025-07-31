<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantFileImportTransfer;

interface MerchantFileImportFormDataProviderInterface
{
    /**
     * @return \Generated\Shared\Transfer\MerchantFileImportTransfer
     */
    public function getData(): MerchantFileImportTransfer;

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array;
}
