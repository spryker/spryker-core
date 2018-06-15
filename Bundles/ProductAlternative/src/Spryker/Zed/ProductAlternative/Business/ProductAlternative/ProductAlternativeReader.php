<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business\ProductAlternative;

use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeListItemTransfer;
use Generated\Shared\Transfer\ProductAlternativeListTransfer;
use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToLocaleFacadeInterface;
use Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToProductFacadeInterface;
use Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface;

class ProductAlternativeReader implements ProductAlternativeReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface
     */
    protected $productAlternativeRepository;

    /**
     * @var \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface $productAlternativeRepository
     * @param \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToProductFacadeInterface $productFacade
     */
    public function __construct(
        ProductAlternativeRepositoryInterface $productAlternativeRepository,
        ProductAlternativeToLocaleFacadeInterface $localeFacade,
        ProductAlternativeToProductFacadeInterface $productFacade
    ) {
        $this->productAlternativeRepository = $productAlternativeRepository;
        $this->localeFacade = $localeFacade;
        $this->productFacade = $productFacade;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListTransfer
     */
    public function getProductAlternativeListByIdProductConcrete(int $idProductConcrete): ProductAlternativeListTransfer
    {
        $productAlternativeCollection = $this->productAlternativeRepository
                    ->getProductAlternativesForProductConcrete($idProductConcrete);

        return $this->hydrateProductAlternativeList(
            $productAlternativeCollection,
            new ProductAlternativeListTransfer()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer $productAlternativeCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductAlternativeListTransfer $productAlternativeListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListTransfer
     */
    protected function hydrateProductAlternativeList(
        ProductAlternativeCollectionTransfer $productAlternativeCollectionTransfer,
        ProductAlternativeListTransfer $productAlternativeListTransfer
    ): ProductAlternativeListTransfer {
        foreach ($productAlternativeCollectionTransfer->getProductAlternatives() as $productAlternativeTransfer) {
            if ($productAlternativeTransfer->getIdProductAbstractAlternative()) {
                $productAlternativeListItemTransfer = $this->hydrateProductAbstractListItemTransfer(
                    $productAlternativeTransfer
                );
                $productAlternativeListTransfer->addProductAlternative($productAlternativeListItemTransfer);
                continue;
            }

            $productAlternativeListItemTransfer = $this->hydrateProductConcreteListItemTransfer(
                $productAlternativeTransfer
            );
            $productAlternativeListTransfer->addProductAlternative($productAlternativeListItemTransfer);
        }

        return $productAlternativeListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    protected function hydrateProductAbstractListItemTransfer(
        ProductAlternativeTransfer $productAlternativeTransfer
    ): ProductAlternativeListItemTransfer {
        $idProductAbstractAlternative = $productAlternativeTransfer->getIdProductAbstractAlternative();
        $productAbstractListItemTransfer = $this->productAlternativeRepository
            ->getProductAlternativeListItemTransferForProductAbstract(
                $idProductAbstractAlternative,
                $this->localeFacade->getCurrentLocale()
            )
            ->setStatus($this->productFacade->isProductActive($idProductAbstractAlternative))
            ->setIdProductAlternative($productAlternativeTransfer->getIdProductAlternative());

        return $productAbstractListItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    protected function hydrateProductConcreteListItemTransfer(
        ProductAlternativeTransfer $productAlternativeTransfer
    ): ProductAlternativeListItemTransfer {
        $productConcreteListItemTransfer = $this->productAlternativeRepository
            ->getProductAlternativeListItemTransferForProductConcrete(
                $productAlternativeTransfer->getIdProductConcreteAlternative(),
                $this->localeFacade->getCurrentLocale()
            )
            ->setIdProductAlternative($productAlternativeTransfer->getIdProductAlternative());

        return $productConcreteListItemTransfer;
    }
}
