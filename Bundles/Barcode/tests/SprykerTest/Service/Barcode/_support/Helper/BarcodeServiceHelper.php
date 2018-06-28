<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Barcode\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Spryker\Service\Barcode\BarcodeServiceInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

/**
 * @method \Spryker\Shared\Kernel\LocatorLocatorInterface|\Generated\Zed\Ide\AutoCompletion|\Generated\Service\Ide\AutoCompletion getLocator()
 */
class BarcodeServiceHelper extends Module
{
    protected const GENERATED_CODE = 'generated string';

    use LocatorHelperTrait;

    /**
     * @return \Spryker\Service\Barcode\BarcodeServiceInterface
     */
    public function getBarcodeService(): BarcodeServiceInterface
    {
        return $this->getLocator()
            ->barcode()
            ->service();
    }

    /**
     * @param null|string $generatorPlugin
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcodeUsingBarcodeService(?string $generatorPlugin = null): BarcodeResponseTransfer
    {
        return $this
            ->getBarcodeService()
            ->generateBarcode(
                static::GENERATED_CODE,
                $generatorPlugin
            );
    }
}
