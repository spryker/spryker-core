<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Reader\Product;

use ArrayObject;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Formatter\ProductLabelFormatterInterface;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToProductFacadeInterface;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Persistence\CmsSlotBlockProductCategoryGuiRepositoryInterface;

class ProductReader implements ProductReaderInterface
{
    protected const KEY_RESULTS = 'results';
    protected const KEY_PAGINATION = 'pagination';
    protected const KEY_PAGINATION_MORE = 'more';
    protected const KEY_DATA_ID = 'id';
    protected const KEY_DATA_TEXT = 'text';

    protected const DEFAULT_ITEMS_PER_PAGE = 10;

    /**
     * @var \Spryker\Zed\CmsSlotBlockProductCategoryGui\Persistence\CmsSlotBlockProductCategoryGuiRepositoryInterface
     */
    protected $cmsSlotBlockProductCategoryGuiRepository;

    /**
     * @var \Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Formatter\ProductLabelFormatterInterface
     */
    protected $productLabelFormatter;

    /**
     * @param \Spryker\Zed\CmsSlotBlockProductCategoryGui\Persistence\CmsSlotBlockProductCategoryGuiRepositoryInterface $cmsSlotBlockProductCategoryGuiRepository
     * @param \Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Formatter\ProductLabelFormatterInterface $productLabelFormatter
     */
    public function __construct(
        CmsSlotBlockProductCategoryGuiRepositoryInterface $cmsSlotBlockProductCategoryGuiRepository,
        CmsSlotBlockProductCategoryGuiToProductFacadeInterface $productFacade,
        ProductLabelFormatterInterface $productLabelFormatter
    ) {
        $this->cmsSlotBlockProductCategoryGuiRepository = $cmsSlotBlockProductCategoryGuiRepository;
        $this->productFacade = $productFacade;
        $this->productLabelFormatter = $productLabelFormatter;
    }

    /**
     * @param int[]|null $productAbstractIds
     *
     * @return int[]
     */
    public function getProductAbstracts(?array $productAbstractIds = []): array
    {
        $productAbstractTransfers = $this->cmsSlotBlockProductCategoryGuiRepository
            ->getProductAbstracts($productAbstractIds);

        return $this->mapProductAbstractTransfersToArray($productAbstractTransfers);
    }

    /**
     * @param string $suggestion
     * @param int $page
     *
     * @return array
     */
    public function getProductAbstractPaginatedAutocompleteData(string $suggestion, int $page): array
    {
        $paginationTransfer = $this->getPaginationTransfer($page);

        /** @var \Generated\Shared\Transfer\ProductAbstractSuggestionCollectionTransfer $productAbstractSuggestionCollectionTransfer */
        $productAbstractSuggestionCollectionTransfer = $this->productFacade
            ->getPaginatedProductAbstractSuggestions($suggestion, $paginationTransfer);

        return [
            static::KEY_RESULTS => $this->mapProductAbstractSuggestionsToAutocompleteData(
                $productAbstractSuggestionCollectionTransfer->getProductAbstracts()
            ),
            static::KEY_PAGINATION => $this->getPaginationData(
                $productAbstractSuggestionCollectionTransfer->getPagination()
            ),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer[] $productAbstractTransfers
     *
     * @return int[]
     */
    protected function mapProductAbstractTransfersToArray(array $productAbstractTransfers): array
    {
        $productIds = [];

        foreach ($productAbstractTransfers as $productAbstractTransfer) {
            $label = $this->productLabelFormatter->format(
                $productAbstractTransfer->getName(),
                $productAbstractTransfer->getSku()
            );
            $productIds[$label] = $productAbstractTransfer->getIdProductAbstract();
        }

        return $productIds;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductAbstractTransfer[] $productAbstractTransfers
     *
     * @return array
     */
    protected function mapProductAbstractSuggestionsToAutocompleteData(ArrayObject $productAbstractTransfers): array
    {
        $autocompleteData = [];

        foreach ($productAbstractTransfers as $productAbstractTransfer) {
            $autocompleteData[] = [
                static::KEY_DATA_ID => $productAbstractTransfer->getIdProductAbstract(),
                static::KEY_DATA_TEXT => $this->productLabelFormatter->format(
                    $productAbstractTransfer->getName(),
                    $productAbstractTransfer->getSku()
                ),
            ];
        }

        return $autocompleteData;
    }

    /**
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return array
     */
    protected function getPaginationData(PaginationTransfer $paginationTransfer): array
    {
        $hasMoreResults = $paginationTransfer->getLastPage() > 0 &&
            $paginationTransfer->getLastPage() !== $paginationTransfer->getPage();

        return [
            static::KEY_PAGINATION_MORE => $hasMoreResults,
        ];
    }

    /**
     * @param int $page
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function getPaginationTransfer(int $page): PaginationTransfer
    {
        return (new PaginationTransfer())
            ->setPage($page)
            ->setMaxPerPage(static::DEFAULT_ITEMS_PER_PAGE);
    }
}
