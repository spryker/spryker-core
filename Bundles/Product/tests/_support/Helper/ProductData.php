<?php
namespace Product\Helper;

use Generated\Shared\DataBuilder\ProductAbstractBuilder;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Testify\Module\BusinessLocator;

class ProductData extends \Codeception\Module
{
    /**
     * @param array $override
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function haveProduct($override = [])
    {
        $productFacade = $this->getProductFacade();
        $abstractProjectId = $productFacade->createProductAbstract((new ProductAbstractBuilder())->build());
        $product = (new ProductConcreteBuilder(['fkProductAbstract' => $abstractProjectId]))
            ->seed($override)
            ->build();
        $productFacade->createProductConcrete($product);
        return $product;
    }

    /**
     * @return \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    private function getProductFacade()
    {
        $locator = $this->getLocator();

        return $locator->getLocator()->product()->facade();
    }

    /**
     * @return BusinessLocator
     */
    private function getLocator()
    {
        return $this->getModule('\\' . BusinessLocator::class);
    }
}