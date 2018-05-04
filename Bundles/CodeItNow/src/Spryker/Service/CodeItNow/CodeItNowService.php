<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\CodeItNow;

use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\CodeItNow\CodeItNowServiceFactory getFactory()
 */
class CodeItNowService extends AbstractService implements CodeItNowServiceInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $text
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateCode128Barcode(string $text): BarcodeResponseTransfer
    {
        return $this->getFactory()
            ->createCode128Generator()
            ->generate($text);
    }
}
