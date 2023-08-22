<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Generated\Shared\Transfer\ProductsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\TaxSetConditionsTransfer;
use Generated\Shared\Transfer\TaxSetCriteriaTransfer;
use Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToLocaleFacadeInterface;
use Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToStoreFacadeInterface;
use Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToTaxFacadeInterface;

class ProductAbstractMapper implements ProductAbstractMapperInterface
{
    /**
     * @var \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToLocaleFacadeInterface
     */
    protected ProductsBackendApiToLocaleFacadeInterface $localeFacade;

    /**
     * @var \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToTaxFacadeInterface
     */
    protected ProductsBackendApiToTaxFacadeInterface $taxFacade;

    /**
     * @var \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToStoreFacadeInterface
     */
    protected ProductsBackendApiToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToTaxFacadeInterface $taxFacade
     * @param \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        ProductsBackendApiToLocaleFacadeInterface $localeFacade,
        ProductsBackendApiToTaxFacadeInterface $taxFacade,
        ProductsBackendApiToStoreFacadeInterface $storeFacade
    ) {
        $this->localeFacade = $localeFacade;
        $this->taxFacade = $taxFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductsBackendApiAttributesTransfer $productsBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function mapProductsBackendApiAttributesTransferToProductAbstractTransfer(
        ProductsBackendApiAttributesTransfer $productsBackendApiAttributesTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer {
        $productAbstractTransfer = $productAbstractTransfer
            ->fromArray($productsBackendApiAttributesTransfer->toArray(), true);

        if ($productsBackendApiAttributesTransfer->getProductAbstractSku()) {
            $productAbstractTransfer->setSku($productsBackendApiAttributesTransfer->getProductAbstractSku());
        }

        $productAbstractTransfer = $this->mapTaxSetId($productsBackendApiAttributesTransfer, $productAbstractTransfer);

        $storeRelationTransfer = $this->mapStoreRelations($productsBackendApiAttributesTransfer);
        $productAbstractTransfer->setStoreRelation($storeRelationTransfer);

        $localizedAttributesTransfers = $this->mapProductLocalizedAttributesBackendApiAttributesTransfersToLocalizedAttributesTransfers($productsBackendApiAttributesTransfer->getLocalizedAttributes());
        $productAbstractTransfer->setLocalizedAttributes($localizedAttributesTransfers);

        $productImageSetTransfers = $this->mapProductImageSetBackendApiAttributesTransfersToProductImageSetTransfers($productsBackendApiAttributesTransfer->getImageSets());
        $productAbstractTransfer->setImageSets($productImageSetTransfers);

        return $productAbstractTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductConcretesBackendApiAttributesTransfer> $productConcretesBackendApiAttributesTransfers
     * @param string $productAbstractSku
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function mapProductConcretesBackendApiAttributesTransfersToProductConcreteTransfers(
        ArrayObject $productConcretesBackendApiAttributesTransfers,
        string $productAbstractSku
    ): array {
        $productConcreteTransfers = [];

        foreach ($productConcretesBackendApiAttributesTransfers as $productConcretesBackendApiAttributesTransfer) {
            $productConcreteTransfer = (new ProductConcreteTransfer())
                ->fromArray($productConcretesBackendApiAttributesTransfer->toArray(), true)
                ->setAbstractSku($productAbstractSku);

            $localizedAttributesTransfers = $this->mapProductConcreteLocalizedAttributesBackendApiAttributesTransfersToLocalizedAttributesTransfers(
                $productConcretesBackendApiAttributesTransfer->getLocalizedAttributes(),
            );
            $productConcreteTransfer->setLocalizedAttributes($localizedAttributesTransfers);

            if ($productConcretesBackendApiAttributesTransfer->getImageSets()->count()) {
                $productImageSetsTransfers = $this->mapProductImageSetBackendApiAttributesTransfersToProductImageSetTransfers($productConcretesBackendApiAttributesTransfer->getImageSets());
                $productConcreteTransfer->setImageSets($productImageSetsTransfers);
            }

            $productConcreteTransfers[] = $productConcreteTransfer;
        }

        return $productConcreteTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductsBackendApiAttributesTransfer $productsBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function mapTaxSetId(
        ProductsBackendApiAttributesTransfer $productsBackendApiAttributesTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer {
        if (!$productsBackendApiAttributesTransfer->getTaxSetName()) {
            return $productAbstractTransfer;
        }

        $taxSetCriteriaTransfer = (new TaxSetCriteriaTransfer())
            ->setTaxSetConditions(
                (new TaxSetConditionsTransfer())
                    ->addName($productsBackendApiAttributesTransfer->getTaxSetName()),
            );
        $taxSetCollectionTransfer = $this->taxFacade->getTaxSetCollection($taxSetCriteriaTransfer);

        if ($taxSetCollectionTransfer->getTaxSets()->count()) {
            $productAbstractTransfer->setIdTaxSet($taxSetCollectionTransfer->getTaxSets()->offsetGet(0)->getIdTaxSet());
        }

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductsBackendApiAttributesTransfer $productsBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    protected function mapStoreRelations(ProductsBackendApiAttributesTransfer $productsBackendApiAttributesTransfer): StoreRelationTransfer
    {
        $storeTransfers = $this->storeFacade->getStoreTransfersByStoreNames($productsBackendApiAttributesTransfer->getStores());
        $storeRelationTransfer = (new StoreRelationTransfer())
            ->setStores(new ArrayObject($storeTransfers));

        $idStores = [];
        foreach ($storeTransfers as $storeTransfer) {
            $idStores[] = $storeTransfer->getIdStoreOrFail();
        }
        $storeRelationTransfer->setIdStores($idStores);

        return $storeRelationTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetBackendApiAttributesTransfer> $productImageSetBackendApiAttributesTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    protected function mapProductImageSetBackendApiAttributesTransfersToProductImageSetTransfers(
        ArrayObject $productImageSetBackendApiAttributesTransfers
    ): ArrayObject {
        $productImageSetsTransfers = [];
        foreach ($productImageSetBackendApiAttributesTransfers as $productImageSetBackendApiAttributesTransfer) {
            $productImageSetsTransfer = (new ProductImageSetTransfer())
                ->setName($productImageSetBackendApiAttributesTransfer->getName());
            if ($productImageSetBackendApiAttributesTransfer->getLocale()) {
                $localeTransfer = $this->localeFacade->getLocale($productImageSetBackendApiAttributesTransfer->getLocaleOrFail());
                $productImageSetsTransfer->setLocale($localeTransfer);
            }
            foreach ($productImageSetBackendApiAttributesTransfer->getImages() as $productImageSetImagesBackendApiAttributesTransfer) {
                $productImageSetsTransfer->addProductImage(
                    (new ProductImageTransfer())->fromArray($productImageSetImagesBackendApiAttributesTransfer->toArray(), true),
                );
            }
            $productImageSetsTransfers[] = $productImageSetsTransfer;
        }

        return new ArrayObject($productImageSetsTransfers);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductLocalizedAttributesBackendApiAttributesTransfer> $productLocalizedAttributesBackendApiAttributesTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer>
     */
    protected function mapProductLocalizedAttributesBackendApiAttributesTransfersToLocalizedAttributesTransfers(
        ArrayObject $productLocalizedAttributesBackendApiAttributesTransfers
    ): ArrayObject {
        $localizedAttributesTransfers = [];
        if ($productLocalizedAttributesBackendApiAttributesTransfers->count()) {
            foreach ($productLocalizedAttributesBackendApiAttributesTransfers as $productLocalizedAttributesBackendApiAttributesTransfer) {
                $localeTransfer = $this->localeFacade->getLocale($productLocalizedAttributesBackendApiAttributesTransfer->getLocaleOrFail());
                $localizedAttributesTransfer = (new LocalizedAttributesTransfer())
                    ->fromArray($productLocalizedAttributesBackendApiAttributesTransfer->toArray(), true)
                    ->setLocale($localeTransfer);

                $localizedAttributesTransfers[] = $localizedAttributesTransfer;
            }

            return new ArrayObject($localizedAttributesTransfers);
        }

        return new ArrayObject();
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductConcreteLocalizedAttributesBackendApiAttributesTransfer> $productConcreteLocalizedAttributesBackendApiAttributesTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer>
     */
    protected function mapProductConcreteLocalizedAttributesBackendApiAttributesTransfersToLocalizedAttributesTransfers(
        ArrayObject $productConcreteLocalizedAttributesBackendApiAttributesTransfers
    ): ArrayObject {
        $localizedAttributesTransfers = [];

        $localeTransfers = $this->localeFacade->getLocaleCollection();
        if ($productConcreteLocalizedAttributesBackendApiAttributesTransfers->count()) {
            foreach ($productConcreteLocalizedAttributesBackendApiAttributesTransfers as $productsProductConcreteLocalizedAttributesAttributesTransfer) {
                $localizedAttributesTransfers[] = (new LocalizedAttributesTransfer())
                    ->fromArray($productsProductConcreteLocalizedAttributesAttributesTransfer->toArray(), true)
                    ->setLocale($localeTransfers[$productsProductConcreteLocalizedAttributesAttributesTransfer->getLocaleOrFail()]);
            }

            return new ArrayObject($localizedAttributesTransfers);
        }

        return new ArrayObject();
    }
}
