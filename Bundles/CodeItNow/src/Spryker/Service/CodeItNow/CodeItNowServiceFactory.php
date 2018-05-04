<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\CodeItNow;

use Spryker\Service\CodeItNow\Generator\BarcodeGeneratorInterface;
use Spryker\Service\CodeItNow\Generator\Code128BarcodeGenerator;
use Spryker\Service\Kernel\AbstractServiceFactory;

class CodeItNowServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\CodeItNow\Generator\BarcodeGeneratorInterface
     */
    public function createCode128Generator(): BarcodeGeneratorInterface
    {
        return new Code128BarcodeGenerator();
    }
}
