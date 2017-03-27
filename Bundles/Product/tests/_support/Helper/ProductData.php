<?php
namespace Product\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductAbstractBuilder;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Testify\Helper\BusinessHelper;

class ProductData extends Module
{

    /**
     * @param array $override
     *
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
        return $this->getModule('\\' . BusinessHelper::class)->getLocator()->product()->facade();
    }

}
