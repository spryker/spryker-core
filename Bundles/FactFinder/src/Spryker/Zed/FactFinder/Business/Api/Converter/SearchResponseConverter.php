<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Converter;

use FACTFinder\Adapter\Search as FFSearchAdapter;
use FACTFinder\Data\AfterSearchNavigation;
use FACTFinder\Data\ArticleNumberSearchStatus;
use FACTFinder\Data\BreadCrumbTrail;
use FACTFinder\Data\CampaignIterator;
use FACTFinder\Data\Paging;
use FACTFinder\Data\Result;
use FACTFinder\Data\ResultsPerPageOptions;
use FACTFinder\Data\Sorting;
use Generated\Shared\Transfer\FactFinderSearchResponseTransfer;

class SearchResponseConverter extends BaseConverter
{

    /**
     * @var \FACTFinder\Adapter\Search
     */
    protected $searchAdapter;

    /**
     * @var \Generated\Shared\Transfer\FactFinderSearchResponseTransfer
     */
    protected $responseTransfer;

    /**
     * @param \FACTFinder\Adapter\Search $searchAdapter
     */
    public function __construct(FFSearchAdapter $searchAdapter)
    {
        $this->searchAdapter = $searchAdapter;
    }

    /**
     * @return \Generated\Shared\Transfer\FactFinderSearchResponseTransfer
     */
    public function convert()
    {
        $this->responseTransfer = new FactFinderSearchResponseTransfer();

        $this->convertCampaigns($this->searchAdapter->getCampaigns());
        $this->convertAfterSearchNavigation($this->searchAdapter->getAfterSearchNavigation());
        $this->convertArticleNumberSearchStatus($this->searchAdapter->getArticleNumberStatus());
        $this->convertBreadCrumbTrail($this->searchAdapter->getBreadCrumbTrail());
        $this->convertPaging($this->searchAdapter->getPaging());
        $this->convertResult($this->searchAdapter->getResult());
        $this->convertResultsPerPageOptions($this->searchAdapter->getResultsPerPageOptions());
        $this->convertSingleWordSearch($this->searchAdapter->getSingleWordSearch());
        $this->convertSorting($this->searchAdapter->getSorting());

//        $followSearchValue = $this->searchAdapter->getFollowSearchValue();

        try {
        } catch (\Exception $e) {
        }

        return $this->responseTransfer;
    }



    /**
     * @param \FACTFinder\Data\CampaignIterator $campaigns
     */
    protected function convertCampaigns(CampaignIterator $campaigns)
    {
//        if ($campaigns->hasRedirect()) {
//            //throw new RedirectException($campaigns->getRedirectUrl());
//            $redirectUrl = $campaigns->getRedirectUrl();
//        }
    }

    /**
     * @param \FACTFinder\Data\AfterSearchNavigation $afterSearchNavigation
     */
    protected function convertAfterSearchNavigation(AfterSearchNavigation $afterSearchNavigation)
    {

    }

    /**
     * @param \FACTFinder\Data\ArticleNumberSearchStatus $articleNumberStatus
     */
    protected function convertArticleNumberSearchStatus(ArticleNumberSearchStatus $articleNumberStatus)
    {

    }

    /**
     * @param \FACTFinder\Data\BreadCrumbTrail $breadCrumbTrail
     */
    protected function convertBreadCrumbTrail(BreadCrumbTrail $breadCrumbTrail)
    {

    }

    /**
     * @param \FACTFinder\Data\Paging $paging
     */
    protected function convertPaging(Paging $paging)
    {

    }

    /**
     * @param \FACTFinder\Data\Result $result
     */
    protected function convertResult(Result $result)
    {

    }

    /**
     * @param \FACTFinder\Data\ResultsPerPageOptions $resultsPerPageOptions
     */
    protected function convertResultsPerPageOptions(ResultsPerPageOptions $resultsPerPageOptions)
    {

    }

    /**
     * @param \FACTFinder\Data\SingleWordSearchItem[] $singleWordSearch
     */
    protected function convertSingleWordSearch($singleWordSearch)
    {

    }

    /**
     * @param \FACTFinder\Data\Sorting $sorting
     */
    protected function convertSorting(Sorting $sorting)
    {

    }


}
