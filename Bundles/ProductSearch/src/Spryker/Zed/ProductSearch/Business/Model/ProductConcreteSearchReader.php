<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Model;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchRepositoryInterface;

class ProductConcreteSearchReader extends AbstractProductSearchReader implements ProductConcreteSearchReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductSearch\Persistence\ProductSearchRepositoryInterface
     */
    protected $productSearchRepository;

    /**
     * @param \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface $productSearchQueryContainer
     * @param \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductSearch\Persistence\ProductSearchRepositoryInterface $productSearchRepository
     */
    public function __construct(
        ProductSearchQueryContainerInterface $productSearchQueryContainer,
        ProductSearchToLocaleInterface $localeFacade,
        ProductSearchRepositoryInterface $productSearchRepository
    ) {
        parent::__construct($productSearchQueryContainer, $localeFacade);

        $this->productSearchRepository = $productSearchRepository;
    }

    /**
     * @deprecated Will be removed without replacement in the next major.
     *
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return bool
     */
    public function isProductConcreteSearchable($idProductConcrete, ?LocaleTransfer $localeTransfer = null)
    {
        $idLocale = $this->getIdLocale($localeTransfer);

        $searchableCount = $this->productSearchQueryContainer
            ->queryProductSearch()
            ->filterByFkProduct($idProductConcrete)
            ->filterByIsSearchable(true)
            ->filterByFkLocale($idLocale)
            ->count();

        return ($searchableCount > 0);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function expandProductConcreteTransfersWithIsSearchable(array $productConcreteTransfers): array
    {
        $productIds = [];
        $localeIds = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributesTransfer) {
                $productIds[] = $productConcreteTransfer->getIdProductConcreteOrFail();
                $localeIds[] = $localizedAttributesTransfer->getLocaleOrFail()->getIdLocaleOrFail();
            }
        }

        $productSearchEntitiesCount = $this->productSearchRepository->getProductSearchEntitiesCountGroupedByIdProductAndIdLocale(
            $productIds,
            $localeIds,
        );

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributesTransfer) {
                $idProductConcrete = $productConcreteTransfer->getIdProductConcreteOrFail();
                $idLocale = $localizedAttributesTransfer->getLocaleOrFail()->getIdLocaleOrFail();
                $isSearchable = ($productSearchEntitiesCount[$idProductConcrete][$idLocale] ?? 0) > 0;
                $localizedAttributesTransfer->setIsSearchable($isSearchable);
            }
        }

        return $productConcreteTransfers;
    }
}
