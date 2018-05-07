<?php

namespace SprykerTest\Zed\ProductBarcode\Helper;

use Codeception\Module;
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
}
