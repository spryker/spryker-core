<?php
namespace Product\Helper;

use Generated\Shared\DataBuilder\ProductAbstractBuilder;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Testify\Helper\AbstractDataHelper;
use Testify\Helper\BusinessHelper;

class ProductData extends AbstractDataHelper
{

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function haveProduct($override = [])
    {
        $productFacade = $this->getProductFacade();
        $abstractProductId = $productFacade->createProductAbstract((new ProductAbstractBuilder())->build());

        $product = (new ProductConcreteBuilder(['fkProductAbstract' => $abstractProductId]))
            ->seed($override)
            ->build();

        $productFacade->createProductConcrete($product);
        $this->debug("Inserted AbstractProduct: $abstractProductId, Concrete Product: ".$product->getIdProductConcrete());

        $this->cleanups[] = function() use ($product, $abstractProductId) {
            $this->debug("Deleting AbstractProduct: $abstractProductId, Concrete Product: ".$product->getIdProductConcrete());
            $this->getProductQuery()->queryProduct()->findByIdProduct($product->getIdProductConcrete())->delete();
            $this->getProductQuery()->queryProductAbstract()->findByIdProductAbstract($abstractProductId)->delete();
        };

        return $product;
    }

    /**
     * @return \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    private function getProductFacade()
    {
        return $this->getModule('\\' . BusinessHelper::class)->getLocator()->product()->facade();
    }

    /**
     * @return \Spryker\Zed\Product\Persistence\ProductQueryContainer
     */
    private function getProductQuery()
    {
        return $this->getModule('\\' . BusinessHelper::class)->getLocator()->product()->queryContainer();
    }
    
    
}
