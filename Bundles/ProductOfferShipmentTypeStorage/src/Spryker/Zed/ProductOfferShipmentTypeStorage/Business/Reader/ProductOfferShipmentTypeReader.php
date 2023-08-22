<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeIteratorConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeIteratorCriteriaTransfer;
use Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Extractor\ProductOfferShipmentTypeExtractorInterface;
use Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToProductOfferShipmentTypeFacadeInterface;
use Spryker\Zed\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageConfig;

class ProductOfferShipmentTypeReader implements ProductOfferShipmentTypeReaderInterface
{
    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const PRODUCT_OFFER_APPROVAL_STATUS_APPROVED = 'approved';

    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageConfig
     */
    protected ProductOfferShipmentTypeStorageConfig $productOfferShipmentTypeStorageConfig;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Extractor\ProductOfferShipmentTypeExtractorInterface
     */
    protected ProductOfferShipmentTypeExtractorInterface $productOfferShipmentTypeExtractor;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToProductOfferShipmentTypeFacadeInterface
     */
    protected ProductOfferShipmentTypeStorageToProductOfferShipmentTypeFacadeInterface $productOfferShipmentTypeFacade;

    /**
     * @var list<\Spryker\Zed\ProductOfferShipmentTypeStorageExtension\Dependency\Plugin\ProductOfferShipmentTypeStorageFilterPluginInterface>
     */
    protected array $productOfferShipmentTypeStorageFilterPlugins;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageConfig $productOfferShipmentTypeStorageConfig
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Extractor\ProductOfferShipmentTypeExtractorInterface $productOfferShipmentTypeExtractor
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToProductOfferShipmentTypeFacadeInterface $productOfferShipmentTypeFacade
     * @param list<\Spryker\Zed\ProductOfferShipmentTypeStorageExtension\Dependency\Plugin\ProductOfferShipmentTypeStorageFilterPluginInterface> $productOfferShipmentTypeStorageFilterPlugins
     */
    public function __construct(
        ProductOfferShipmentTypeStorageConfig $productOfferShipmentTypeStorageConfig,
        ProductOfferShipmentTypeExtractorInterface $productOfferShipmentTypeExtractor,
        ProductOfferShipmentTypeStorageToProductOfferShipmentTypeFacadeInterface $productOfferShipmentTypeFacade,
        array $productOfferShipmentTypeStorageFilterPlugins
    ) {
        $this->productOfferShipmentTypeStorageConfig = $productOfferShipmentTypeStorageConfig;
        $this->productOfferShipmentTypeExtractor = $productOfferShipmentTypeExtractor;
        $this->productOfferShipmentTypeFacade = $productOfferShipmentTypeFacade;
        $this->productOfferShipmentTypeStorageFilterPlugins = $productOfferShipmentTypeStorageFilterPlugins;
    }

    /**
     * @param list<int> $productOfferIds
     *
     * @return iterable<\ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer>>
     */
    public function getProductOfferShipmentTypeIteratorByProductOfferIds(array $productOfferIds): iterable
    {
        $productOfferShipmentTypeIteratorCriteriaTransfer = $this->createProductOfferShipmentTypeIteratorCriteriaTransfer(
            $productOfferIds,
        );

        return $this->getProductOfferShipmentTypeIterator($productOfferShipmentTypeIteratorCriteriaTransfer);
    }

    /**
     * @param list<int> $productOfferShipmentTypeIds
     *
     * @return iterable<\ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer>>
     */
    public function getProductOfferShipmentTypeIteratorByProductOfferShipmentTypeIds(
        array $productOfferShipmentTypeIds
    ): iterable {
        $productOfferShipmentTypeConditionsTransfer = (new ProductOfferShipmentTypeConditionsTransfer())
            ->setProductOfferShipmentTypeIds($productOfferShipmentTypeIds)
            ->setGroupByIdProductOffer(true);
        $productOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeCriteriaTransfer())
            ->setProductOfferShipmentTypeConditions($productOfferShipmentTypeConditionsTransfer);

        $productOfferShipmentTypeCollectionTransfer = $this->productOfferShipmentTypeFacade
            ->getProductOfferShipmentTypeCollection($productOfferShipmentTypeCriteriaTransfer);

        $productOfferIds = $this->productOfferShipmentTypeExtractor->extractProductOfferIdsFromProductOfferShipmentTypeTransfers(
            $productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes(),
        );

        return $this->getProductOfferShipmentTypeIteratorByProductOfferIds($productOfferIds);
    }

    /**
     * @param list<int> $shipmentTypeIds
     *
     * @return iterable<\ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer>>
     */
    public function getProductOfferShipmentTypeIteratorByShipmentTypeIds(array $shipmentTypeIds): iterable
    {
        $batchSize = $this->productOfferShipmentTypeStorageConfig->getReadCollectionBatchSize();
        $offset = 0;
        do {
            $productOfferShipmentTypeConditionsTransfer = (new ProductOfferShipmentTypeConditionsTransfer())
                ->setShipmentTypeIds($shipmentTypeIds)
                ->setGroupByIdProductOffer(true);
            $paginationTransfer = (new PaginationTransfer())->setOffset($offset)->setLimit($batchSize);
            $productOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeCriteriaTransfer())
                ->setProductOfferShipmentTypeConditions($productOfferShipmentTypeConditionsTransfer)
                ->setPagination($paginationTransfer);

            $productOfferShipmentTypeCollectionTransfer = $this->productOfferShipmentTypeFacade->getProductOfferShipmentTypeCollection(
                $productOfferShipmentTypeCriteriaTransfer,
            );

            if ($productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes()->count() === 0) {
                break;
            }

            $productOfferIds = $this->productOfferShipmentTypeExtractor->extractProductOfferIdsFromProductOfferShipmentTypeTransfers(
                $productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes(),
            );

            $productOfferShipmentTypeIterator = $this->getProductOfferShipmentTypeIteratorByProductOfferIds($productOfferIds);
            foreach ($productOfferShipmentTypeIterator as $productOfferShipmentTypeTransfer) {
                yield $productOfferShipmentTypeTransfer;
            }

            $offset += $batchSize;
        } while ($productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes()->count() !== 0);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeIteratorCriteriaTransfer $productOfferShipmentTypeIteratorCriteriaTransfer
     *
     * @return iterable<\ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer>>
     */
    protected function getProductOfferShipmentTypeIterator(
        ProductOfferShipmentTypeIteratorCriteriaTransfer $productOfferShipmentTypeIteratorCriteriaTransfer
    ): iterable {
        $productOfferShipmentTypeTransfersIterator = $this->productOfferShipmentTypeFacade->getProductOfferShipmentTypesIterator(
            $productOfferShipmentTypeIteratorCriteriaTransfer,
        );

        foreach ($productOfferShipmentTypeTransfersIterator as $productOfferShipmentTypeTransfers) {
            yield $this->executeProductOfferShipmentTypeStorageFilterPlugins($productOfferShipmentTypeTransfers);
        }
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer> $productOfferShipmentTypeTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer>
     */
    protected function executeProductOfferShipmentTypeStorageFilterPlugins(
        ArrayObject $productOfferShipmentTypeTransfers
    ): ArrayObject {
        $productOfferShipmentTypeCollectionTransfer = (new ProductOfferShipmentTypeCollectionTransfer())
            ->setProductOfferShipmentTypes($productOfferShipmentTypeTransfers);
        foreach ($this->productOfferShipmentTypeStorageFilterPlugins as $productOfferShipmentTypeStorageFilterPlugin) {
            $productOfferShipmentTypeCollectionTransfer = $productOfferShipmentTypeStorageFilterPlugin->filter(
                $productOfferShipmentTypeCollectionTransfer,
            );
        }

        return $productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes();
    }

    /**
     * @param list<int> $productOfferIds
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeIteratorCriteriaTransfer
     */
    protected function createProductOfferShipmentTypeIteratorCriteriaTransfer(array $productOfferIds): ProductOfferShipmentTypeIteratorCriteriaTransfer
    {
        $productOfferShipmentTypeIteratorConditionsTransfer = (new ProductOfferShipmentTypeIteratorConditionsTransfer())
            ->setProductOfferIds($productOfferIds)
            ->setIsActiveProductOffer(true)
            ->setIsActiveProductOfferConcreteProduct(true)
            ->addProductOfferApprovalStatus(static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED);

        return (new ProductOfferShipmentTypeIteratorCriteriaTransfer())
            ->setProductOfferShipmentTypeIteratorConditions($productOfferShipmentTypeIteratorConditionsTransfer);
    }
}
