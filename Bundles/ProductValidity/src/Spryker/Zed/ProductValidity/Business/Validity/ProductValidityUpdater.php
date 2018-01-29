<?php


namespace Spryker\Zed\ProductValidity\Business\Validity;


use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductValidity\Persistence\ProductValidityQueryContainerInterface;

class ProductValidityUpdater implements  ProductValidityUpdaterInterface
{
    /**
     * @var ProductValidityQueryContainerInterface
     */
    protected $productValidityQueryContainer;

    /**
     * @param ProductValidityQueryContainerInterface $productValidityQueryContainer
     */
    public function __construct(ProductValidityQueryContainerInterface $productValidityQueryContainer)
    {
        $this->productValidityQueryContainer = $productValidityQueryContainer;
    }

    /**
     * @param ProductConcreteTransfer $productConcreteTransfer
     *
     * @return ProductConcreteTransfer
     */
    public function update(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        $productConcreteTransfer->requireIdProductConcrete();

        $productValidityEntity = $this->productValidityQueryContainer
            ->queryProductValidityByIdProductConcrete($productConcreteTransfer->getIdProductConcrete())
            ->filterByFkProduct()
            ->findOneOrCreate();

        $productValidityEntity->setValidFrom($productConcreteTransfer->getValidFrom());
        $productValidityEntity->setValidTo($productConcreteTransfer->getValidTo());

        $productValidityEntity->save();


        return $productConcreteTransfer;
    }
}