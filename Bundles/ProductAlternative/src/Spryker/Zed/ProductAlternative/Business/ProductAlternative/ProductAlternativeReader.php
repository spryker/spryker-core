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
     * @var \Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin\AlternativeProductApplicablePluginInterface[]
     */
    protected $productApplicableLabelAlternativePluginInterface;

    /**
     * @param \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface $productAlternativeRepository
     * @param \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin\AlternativeProductApplicablePluginInterface[] $productApplicableLabelAlternativePluginInterface
     */
    public function __construct(
        ProductAlternativeRepositoryInterface $productAlternativeRepository,
        ProductAlternativeToLocaleFacadeInterface $localeFacade,
        ProductAlternativeToProductFacadeInterface $productFacade,
        array $productApplicableLabelAlternativePluginInterface
    ) {
        $this->productAlternativeRepository = $productAlternativeRepository;
        $this->localeFacade = $localeFacade;
        $this->productFacade = $productFacade;
        $this->productApplicableLabelAlternativePluginInterface = $productApplicableLabelAlternativePluginInterface;
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

        return $this->mapProductAlternativeToProductAlternativeItemTransfer(
            $productAlternativeCollection,
            new ProductAlternativeListTransfer()
        );
    }

    /**
     * @param int[] $productIds
     *
     * @return bool
     */
    public function doAllConcreteProductsHaveAlternatives(array $productIds): bool
    {
        return $this->productAlternativeRepository
            ->doAllConcreteProductsHaveAlternatives($productIds);
    }

    /**
     * @param int $idProduct
     *
     * @return bool
     */
    public function isAlternativeProductApplicable(int $idProduct): bool
    {
        foreach ($this->productApplicableLabelAlternativePluginInterface as $productApplicableLabelAlternativePlugin) {
            if ($productApplicableLabelAlternativePlugin->check($idProduct)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return int[]
     */
    public function findProductAbstractIdsWhichConcreteHasAlternative(): array
    {
        return $this->productAlternativeRepository
            ->findProductAbstractIdsWhichConcreteHasAlternative();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer $productAlternativeCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductAlternativeListTransfer $productAlternativeListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListTransfer
     */
    protected function mapProductAlternativeToProductAlternativeItemTransfer(
        ProductAlternativeCollectionTransfer $productAlternativeCollectionTransfer,
        ProductAlternativeListTransfer $productAlternativeListTransfer
    ): ProductAlternativeListTransfer {
        foreach ($productAlternativeCollectionTransfer->getProductAlternatives() as $productAlternativeTransfer) {
            if ($productAlternativeTransfer->getIdProductAbstractAlternative()) {
                $productAlternativeListItemTransfer = $this->getProductAbstractListItemTransfer(
                    $productAlternativeTransfer
                );
                $productAlternativeListTransfer->addProductAlternative($productAlternativeListItemTransfer);
                continue;
            }

            $productAlternativeListItemTransfer = $this->getProductConcreteListItemTransfer(
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
    protected function getProductAbstractListItemTransfer(
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
    protected function getProductConcreteListItemTransfer(
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
