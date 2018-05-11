<?php

namespace SprykerTest\Zed\ProductBarcode\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductBarcode\Business\ProductBarcodeFacadeInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductBarcodeFacadeHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @return \Spryker\Zed\ProductBarcode\Business\ProductBarcodeFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    public function getFacade(): ProductBarcodeFacadeInterface
    {
        return $this->getLocator()
            ->productBarcode()
            ->facade();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param null|string $generatorPlugin
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcode(ProductConcreteTransfer $productConcreteTransfer, ?string $generatorPlugin = null): BarcodeResponseTransfer
    {
        return $this
            ->getFacade()
            ->generateBarcode(
                $productConcreteTransfer,
                $generatorPlugin
            );
    }
}
