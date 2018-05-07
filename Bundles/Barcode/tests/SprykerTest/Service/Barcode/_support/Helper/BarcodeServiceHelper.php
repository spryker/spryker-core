<?php

namespace SprykerTest\Service\Barcode\Helper;

use Codeception\Module;
use Spryker\Service\Barcode\BarcodeServiceInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

/**
 * @method \Spryker\Shared\Kernel\LocatorLocatorInterface|\Generated\Zed\Ide\AutoCompletion|\Generated\Service\Ide\AutoCompletion getLocator()
 */
class BarcodeServiceHelper extends Module
{
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
}
