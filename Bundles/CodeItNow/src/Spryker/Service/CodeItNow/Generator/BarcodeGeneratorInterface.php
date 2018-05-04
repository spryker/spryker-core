<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\CodeItNow\Generator;

use Generated\Shared\Transfer\BarcodeResponseTransfer;

interface BarcodeGeneratorInterface
{
    public const ENCODING = 'data:image/png;base64';

    /**
     * @param string $text
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generate(string $text): BarcodeResponseTransfer;
}
