<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Converter\Data;

use FACTFinder\Data\Page;
use FACTFinder\Data\Paging;
use Generated\Shared\Transfer\FactFinderDataPageTransfer;
use Generated\Shared\Transfer\FactFinderDataPagingTransfer;
use Spryker\Zed\FactFinder\Business\Api\Converter\BaseConverter;

class PagingConverter extends BaseConverter
{

    /**
     * @var \FACTFinder\Data\Paging
     */
    protected $paging;

    /**
     * @var \Spryker\Zed\FactFinder\Business\Api\Converter\Data\ItemConverter
     */
    protected $itemConverter;

    /**
     * @param \Spryker\Zed\FactFinder\Business\Api\Converter\Data\ItemConverter $itemConverter
     */
    public function __construct(
        ItemConverter $itemConverter
    ) {
        $this->itemConverter = $itemConverter;
    }

    /**
     * @param \FACTFinder\Data\Paging $paging
     */
    public function setPaging(Paging $paging)
    {
        $this->paging = $paging;
    }

    /**
     * @return \Generated\Shared\Transfer\FactFinderDataPagingTransfer
     */
    public function convert()
    {
        $factFinderDataPagingTransfer = new FactFinderDataPagingTransfer();
        $factFinderDataPagingTransfer->setPageCount($this->paging->getPageCount());
        $factFinderDataPagingTransfer->setFirstPage($this->convertPage($this->paging->getFirstPage()));
        $factFinderDataPagingTransfer->setLastPage($this->convertPage($this->paging->getLastPage()));
        $factFinderDataPagingTransfer->setPreviousPage($this->convertPage($this->paging->getPreviousPage()));
        $factFinderDataPagingTransfer->setCurrentPage($this->convertPage($this->paging->getCurrentPage()));
        $factFinderDataPagingTransfer->setNextPage($this->convertPage($this->paging->getNextPage()));

        return $factFinderDataPagingTransfer;
    }

    /**
     * @param \FACTFinder\Data\Page|null $page
     * @return FactFinderDataPageTransfer
     */
    protected function convertPage($page)
    {
        $factFinderDataPageTransfer = new FactFinderDataPageTransfer();
        if (is_null($page)) {
            return $factFinderDataPageTransfer;
        }

        $factFinderDataPageTransfer->setPageNumber($page->getPageNumber());
        $this->itemConverter->setItem($page);
        $factFinderDataPageTransfer->setFactFinderDataItem(
            $this->itemConverter->convert()
        );

        return $factFinderDataPageTransfer;
    }

}
