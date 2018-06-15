<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business\ProductAlternative;

use Generated\Shared\Transfer\ProductAlternativeCreateRequestTransfer;
use Generated\Shared\Transfer\ProductAlternativeResponseTransfer;
use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToProductFacadeInterface;
use Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeEntityManagerInterface;
use Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface;

class ProductAlternativeWriter implements ProductAlternativeWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeEntityManagerInterface
     */
    protected $productAlternativeEntityManager;

    /**
     * @var \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeReaderInterface
     */
    protected $productAlternativeReader;

    /**
     * @var \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface
     */
    protected $productAlternativeRepository;

    /**
     * @param \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeEntityManagerInterface $productAlternativeEntityManager
     * @param \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface $productAlternativeRepository
     * @param \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToProductFacadeInterface $productFacade
     */
    public function __construct(
        ProductAlternativeEntityManagerInterface $productAlternativeEntityManager,
        ProductAlternativeRepositoryInterface $productAlternativeRepository,
        ProductAlternativeToProductFacadeInterface $productFacade
    ) {
        $this->productAlternativeEntityManager = $productAlternativeEntityManager;
        $this->productFacade = $productFacade;
        $this->productAlternativeRepository = $productAlternativeRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistProductAlternative(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        $productConcreteTransfer->requireProductAlternativeCreateRequests();
        foreach ($productConcreteTransfer->getProductAlternativeCreateRequests() as $productAlternativeCreateRequestTransfer) {
            $this->createProductAlternative($productAlternativeCreateRequestTransfer);
        }

        return $productConcreteTransfer;
    }

    /**
     * @param int $idProductAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function deleteProductAlternativeByIdProductAlternative(int $idProductAlternative): ProductAlternativeResponseTransfer
    {
        $productAlternativeTransfer = $this->productAlternativeRepository
            ->findProductAlternativeByIdProductAlternative($idProductAlternative);

        $productAlternativeResponseTransfer = (new ProductAlternativeResponseTransfer())
            ->setProductAlternative($productAlternativeTransfer);

        if (!$productAlternativeTransfer) {
            return $productAlternativeResponseTransfer
                ->setIsSuccessful(false);
        }

        $this->productAlternativeEntityManager
            ->deleteProductAlternative($productAlternativeTransfer);

        return $productAlternativeResponseTransfer
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeCreateRequestTransfer $productAlternativeCreateRequestTransfer
     *
     * @return void
     */
    protected function createProductAlternative(ProductAlternativeCreateRequestTransfer $productAlternativeCreateRequestTransfer): void
    {
        $idProductAbstract = $this->productFacade->findProductAbstractIdBySku($productAlternativeCreateRequestTransfer->getAlternativeSku());
        if ($idProductAbstract) {
            $this->createProductAbstractAlternative($productAlternativeCreateRequestTransfer->getIdProduct(), $idProductAbstract);

            return;
        }

        $idProductConcrete = $this->productFacade->findProductConcreteIdBySku($productAlternativeCreateRequestTransfer->getAlternativeSku());
        if ($idProductConcrete) {
            $this->createProductConcreteAlternative($productAlternativeCreateRequestTransfer->getIdProduct(), $idProductConcrete);

            return;
        }
    }

    /**
     * @param int $idProduct
     * @param int $idProductAbstractAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    protected function createProductAbstractAlternative(int $idProduct, int $idProductAbstractAlternative): ProductAlternativeTransfer
    {
        return $this->productAlternativeEntityManager
            ->createProductAbstractAlternative(
                $idProduct,
                $idProductAbstractAlternative
            );
    }

    /**
     * @param int $idProduct
     * @param int $idProductConcreteAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    protected function createProductConcreteAlternative(int $idProduct, int $idProductConcreteAlternative): ProductAlternativeTransfer
    {
        return $this->productAlternativeEntityManager
            ->createProductConcreteAlternative(
                $idProduct,
                $idProductConcreteAlternative
            );
    }
}
