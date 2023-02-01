<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ApiProductsAttributesTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
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
     * @param \Generated\Shared\Transfer\ApiProductsAttributesTransfer $apiProductsAttributesTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function mapApiProductsAttributesTransferToProductAbstractTransfer(
        ApiProductsAttributesTransfer $apiProductsAttributesTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer {
        $productAbstractTransfer = $productAbstractTransfer
            ->fromArray($apiProductsAttributesTransfer->toArray(), true);

        if ($apiProductsAttributesTransfer->getProductAbstractSku()) {
            $productAbstractTransfer->setSku($apiProductsAttributesTransfer->getProductAbstractSku());
        }

        $productAbstractTransfer = $this->mapTaxSetId($apiProductsAttributesTransfer, $productAbstractTransfer);

        $storeRelationTransfer = $this->mapStoreRelations($apiProductsAttributesTransfer);
        $productAbstractTransfer->setStoreRelation($storeRelationTransfer);

        $localizedAttributesTransfers = $this->mapApiProductsLocalizedAttributesAttributesTransfersToLocalizedAttributesTransfers($apiProductsAttributesTransfer->getLocalizedAttributes());
        $productAbstractTransfer->setLocalizedAttributes($localizedAttributesTransfers);

        $productImageSetTransfers = $this->mapApiProductsImageSetAttributesTransfersToProductImageSetTransfers($apiProductsAttributesTransfer->getImageSets());
        $productAbstractTransfer->setImageSets($productImageSetTransfers);

        return $productAbstractTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ApiProductsProductConcreteAttributesTransfer> $apiProductsProductConcreteAttributesTransfers
     * @param string $productAbstractSku
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function mapApiProductsProductConcreteAttributesTransfersToProductConcreteTransfers(
        ArrayObject $apiProductsProductConcreteAttributesTransfers,
        string $productAbstractSku
    ): array {
        $productConcreteTransfers = [];

        foreach ($apiProductsProductConcreteAttributesTransfers as $apiProductsProductConcreteAttributesTransfer) {
            $productConcreteTransfer = (new ProductConcreteTransfer())
                ->fromArray($apiProductsProductConcreteAttributesTransfer->toArray(), true)
                ->setAbstractSku($productAbstractSku);

            $localizedAttributesTransfers = $this->mapApiProductsProductConcreteLocalizedAttributesAttributesTransfersToLocalizedAttributesTransfers(
                $apiProductsProductConcreteAttributesTransfer->getLocalizedAttributes(),
            );
            $productConcreteTransfer->setLocalizedAttributes($localizedAttributesTransfers);

            if ($apiProductsProductConcreteAttributesTransfer->getImageSets()->count()) {
                $productImageSetsTransfers = $this->mapApiProductsImageSetAttributesTransfersToProductImageSetTransfers($apiProductsProductConcreteAttributesTransfer->getImageSets());
                $productConcreteTransfer->setImageSets($productImageSetsTransfers);
            }

            $productConcreteTransfers[] = $productConcreteTransfer;
        }

        return $productConcreteTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiProductsAttributesTransfer $apiProductsAttributesTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function mapTaxSetId(
        ApiProductsAttributesTransfer $apiProductsAttributesTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer {
        if (!$apiProductsAttributesTransfer->getTaxSetName()) {
            return $productAbstractTransfer;
        }

        $taxSetCriteriaTransfer = (new TaxSetCriteriaTransfer())
            ->setTaxSetConditions(
                (new TaxSetConditionsTransfer())
                    ->addName($apiProductsAttributesTransfer->getTaxSetName()),
            );
        $taxSetCollectionTransfer = $this->taxFacade->getTaxSetCollection($taxSetCriteriaTransfer);

        if ($taxSetCollectionTransfer->getTaxSets()->count()) {
            $productAbstractTransfer->setIdTaxSet($taxSetCollectionTransfer->getTaxSets()->offsetGet(0)->getIdTaxSet());
        }

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiProductsAttributesTransfer $apiProductsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    protected function mapStoreRelations(ApiProductsAttributesTransfer $apiProductsAttributesTransfer): StoreRelationTransfer
    {
        $storeTransfers = $this->storeFacade->getStoreTransfersByStoreNames($apiProductsAttributesTransfer->getStores());
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
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ApiProductsImageSetAttributesTransfer> $apiProductsImageSetAttributesTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    protected function mapApiProductsImageSetAttributesTransfersToProductImageSetTransfers(ArrayObject $apiProductsImageSetAttributesTransfers): ArrayObject
    {
        $productImageSetsTransfers = [];
        foreach ($apiProductsImageSetAttributesTransfers as $apiProductsImageSetAttributesTransfer) {
            $productImageSetsTransfer = (new ProductImageSetTransfer())
                ->setName($apiProductsImageSetAttributesTransfer->getName());
            if ($apiProductsImageSetAttributesTransfer->getLocale()) {
                $localeTransfer = $this->localeFacade->getLocale($apiProductsImageSetAttributesTransfer->getLocaleOrFail());
                $productImageSetsTransfer->setLocale($localeTransfer);
            }
            foreach ($apiProductsImageSetAttributesTransfer->getImages() as $apiProductsImageSetImageAttributesTransfer) {
                $productImageSetsTransfer->addProductImage(
                    (new ProductImageTransfer())->fromArray($apiProductsImageSetImageAttributesTransfer->toArray(), true),
                );
            }
            $productImageSetsTransfers[] = $productImageSetsTransfer;
        }

        return new ArrayObject($productImageSetsTransfers);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ApiProductsLocalizedAttributesAttributesTransfer> $apiProductsLocalizedAttributesAttributesTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer>
     */
    protected function mapApiProductsLocalizedAttributesAttributesTransfersToLocalizedAttributesTransfers(
        ArrayObject $apiProductsLocalizedAttributesAttributesTransfers
    ): ArrayObject {
        $localizedAttributesTransfers = [];
        if ($apiProductsLocalizedAttributesAttributesTransfers->count()) {
            foreach ($apiProductsLocalizedAttributesAttributesTransfers as $apiProductsLocalizedAttributesAttributesTransfer) {
                $localeTransfer = $this->localeFacade->getLocale($apiProductsLocalizedAttributesAttributesTransfer->getLocaleOrFail());
                $localizedAttributesTransfer = (new LocalizedAttributesTransfer())
                    ->fromArray($apiProductsLocalizedAttributesAttributesTransfer->toArray(), true)
                    ->setLocale($localeTransfer);

                $localizedAttributesTransfers[] = $localizedAttributesTransfer;
            }

            return new ArrayObject($localizedAttributesTransfers);
        }

        return new ArrayObject();
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ApiProductsProductConcreteLocalizedAttributesAttributesTransfer> $apiProductsProductConcreteLocalizedAttributesAttributesTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer>
     */
    protected function mapApiProductsProductConcreteLocalizedAttributesAttributesTransfersToLocalizedAttributesTransfers(
        ArrayObject $apiProductsProductConcreteLocalizedAttributesAttributesTransfers
    ): ArrayObject {
        $localizedAttributesTransfers = [];

        $localeTransfers = $this->localeFacade->getLocaleCollection();
        if ($apiProductsProductConcreteLocalizedAttributesAttributesTransfers->count()) {
            foreach ($apiProductsProductConcreteLocalizedAttributesAttributesTransfers as $productsProductConcreteLocalizedAttributesAttributesTransfer) {
                $localizedAttributesTransfers[] = (new LocalizedAttributesTransfer())
                    ->fromArray($productsProductConcreteLocalizedAttributesAttributesTransfer->toArray(), true)
                    ->setLocale($localeTransfers[$productsProductConcreteLocalizedAttributesAttributesTransfer->getLocaleOrFail()]);
            }

            return new ArrayObject($localizedAttributesTransfers);
        }

        return new ArrayObject();
    }
}
