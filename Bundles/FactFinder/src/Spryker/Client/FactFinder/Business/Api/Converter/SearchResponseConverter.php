<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder\Business\Api\Converter;

use ArrayObject;
use FACTFinder\Adapter\Search as FFSearchAdapter;
use FACTFinder\Data\AfterSearchNavigation;
use FACTFinder\Data\BreadCrumbTrail;
use FACTFinder\Data\CampaignIterator;
use FACTFinder\Data\Result;
use FACTFinder\Data\ResultsPerPageOptions;
use FACTFinder\Data\Sorting;
use FACTFinder\Data\SuggestQuery;
use Generated\Shared\Transfer\FactFinderDataAfterSearchNavigationTransfer;
use Generated\Shared\Transfer\FactFinderDataBreadCrumbTransfer;
use Generated\Shared\Transfer\FactFinderDataCampaignIteratorTransfer;
use Generated\Shared\Transfer\FactFinderDataCampaignTransfer;
use Generated\Shared\Transfer\FactFinderDataResultsPerPageOptionsTransfer;
use Generated\Shared\Transfer\FactFinderDataResultTransfer;
use Generated\Shared\Transfer\FactFinderDataSingleWordSearchItemTransfer;
use Generated\Shared\Transfer\FactFinderDataSuggestQueryTransfer;
use Generated\Shared\Transfer\FactFinderSearchResponseTransfer;
use Spryker\Client\FactFinder\Business\Api\Converter\Data\AdvisorQuestionConverter;
use Spryker\Client\FactFinder\Business\Api\Converter\Data\FilterGroupConverter;
use Spryker\Client\FactFinder\Business\Api\Converter\Data\ItemConverter;
use Spryker\Client\FactFinder\Business\Api\Converter\Data\PagingConverter;
use Spryker\Client\FactFinder\Business\Api\Converter\Data\RecordConverter;
use Spryker\Shared\FactFinder\FactFinderConstants;

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
     * @var \Spryker\Client\FactFinder\Business\Api\Converter\Data\PagingConverter
     */
    protected $pagingConverter;

    /**
     * @var \Spryker\Client\FactFinder\Business\Api\Converter\Data\ItemConverter
     */
    protected $itemConverter;

    /**
     * @var \Spryker\Client\FactFinder\Business\Api\Converter\Data\RecordConverter
     */
    protected $recordConverter;

    /**
     * @var \Spryker\Client\FactFinder\Business\Api\Converter\Data\FilterGroupConverter
     */
    protected $filterGroupConverter;

    /**
     * @var \Spryker\Client\FactFinder\Business\Api\Converter\Data\AdvisorQuestionConverter
     */
    protected $advisorQuestionConverter;

    /**
     * @param \FACTFinder\Adapter\Search $searchAdapter
     * @param \Spryker\Client\FactFinder\Business\Api\Converter\Data\PagingConverter $pagingConverter
     * @param \Spryker\Client\FactFinder\Business\Api\Converter\Data\ItemConverter $itemConverter
     * @param \Spryker\Client\FactFinder\Business\Api\Converter\Data\RecordConverter $recordConverter
     * @param \Spryker\Client\FactFinder\Business\Api\Converter\Data\FilterGroupConverter $filterGroupConverter
     * @param \Spryker\Client\FactFinder\Business\Api\Converter\Data\AdvisorQuestionConverter $advisorQuestionConverter
     */
    public function __construct(
        FFSearchAdapter $searchAdapter,
        PagingConverter $pagingConverter,
        ItemConverter $itemConverter,
        RecordConverter $recordConverter,
        FilterGroupConverter $filterGroupConverter,
        AdvisorQuestionConverter $advisorQuestionConverter
    ) {
        $this->searchAdapter = $searchAdapter;
        $this->pagingConverter = $pagingConverter;
        $this->itemConverter = $itemConverter;
        $this->recordConverter = $recordConverter;
        $this->filterGroupConverter = $filterGroupConverter;
        $this->advisorQuestionConverter = $advisorQuestionConverter;
    }

    /**
     * @return \Generated\Shared\Transfer\FactFinderSearchResponseTransfer
     */
    public function convert()
    {
        $this->responseTransfer = new FactFinderSearchResponseTransfer();

        $this->responseTransfer->setCampaignIterator(
            $this->convertCampaigns($this->searchAdapter->getCampaigns())
        );
        $this->responseTransfer->setAfterSearchNavigation(
            $this->convertAfterSearchNavigation($this->searchAdapter->getAfterSearchNavigation())
        );
        $this->responseTransfer->setBreadCrumbs(
            $this->convertBreadCrumbTrail($this->searchAdapter->getBreadCrumbTrail())
        );
        $this->pagingConverter->setPaging($this->searchAdapter->getPaging());
        $this->responseTransfer->setPaging(
            $this->pagingConverter->convert()
        );
        $this->responseTransfer->setResult(
            $this->convertResult($this->searchAdapter->getResult())
        );
        $this->responseTransfer->setResultsPerPageOptions(
            $this->convertResultsPerPageOptions($this->searchAdapter->getResultsPerPageOptions())
        );
        $this->responseTransfer->setSingleWordSearchItems(
            $this->convertSingleWordSearch($this->searchAdapter->getSingleWordSearch())
        );
        $this->responseTransfer->setSortingItems(
            $this->convertSorting($this->searchAdapter->getSorting())
        );
        $this->responseTransfer->setIsSearchTimedOut(
            $this->searchAdapter->isSearchTimedOut()
        );
        $this->responseTransfer->setFollowSearchValue(
            $this->searchAdapter->getFollowSearchValue()
        );

        return $this->responseTransfer;
    }

    /**
     * @param \FACTFinder\Data\CampaignIterator $campaigns
     *
     * @return \Generated\Shared\Transfer\FactFinderDataCampaignIteratorTransfer
     */
    protected function convertCampaigns(CampaignIterator $campaigns)
    {
        $factFinderDataCampaignIteratorTransfer = new FactFinderDataCampaignIteratorTransfer();
        $factFinderDataCampaignIteratorTransfer->setHasRedirect($campaigns->hasRedirect());
        $factFinderDataCampaignIteratorTransfer->setRedirectUrl($campaigns->getRedirectUrl());
        $factFinderDataCampaignIteratorTransfer->setHasFeedback($campaigns->hasFeedback());
//        $factFinderDataCampaignIteratorTransfer->setFeedback($campaigns->getFeedback());
        $factFinderDataCampaignIteratorTransfer->setHasPushedProducts($campaigns->hasPushedProducts());
        /** @var \FACTFinder\Data\Record $pushedProduct */
        foreach ($campaigns->getPushedProducts() as $pushedProduct) {
            $this->recordConverter->setRecord($pushedProduct);
            $factFinderDataCampaignIteratorTransfer->addPushedProducts(
                $this->recordConverter->convert()
            );
        }
        $factFinderDataCampaignIteratorTransfer->setHasActiveQuestions($campaigns->hasActiveQuestions());
        /** @var \FACTFinder\Data\Record $activeQuestion */
        foreach ($campaigns->getActiveQuestions() as $activeQuestion) {
            $this->recordConverter->setRecord($activeQuestion);
            $factFinderDataCampaignIteratorTransfer->addGetActiveQuestions(
                $this->recordConverter->convert()
            );
        }
        $factFinderDataCampaignIteratorTransfer->setHasAdvisorTree($campaigns->hasAdvisorTree());
        /** @var \FACTFinder\Data\Record $advisorTree */
        foreach ($campaigns->getAdvisorTree() as $advisorTree) {
            $this->recordConverter->setRecord($advisorTree);
            $factFinderDataCampaignIteratorTransfer->addAdvisorTree(
                $this->recordConverter->convert()
            );
        }

        /** @var \FACTFinder\Data\Campaign $campaign */
        foreach ($campaigns as $campaign) {
            $factFinderDataCampaignTransfer = new FactFinderDataCampaignTransfer();
            $factFinderDataCampaignTransfer->setName($campaign->getName());
            $factFinderDataCampaignTransfer->setCategory($campaign->getCategory());
            $factFinderDataCampaignTransfer->setRedirectUrl($campaign->getRedirectUrl());
            $factFinderDataCampaignTransfer->setFeedback($campaign->getFeedbackArray());
            $factFinderDataCampaignTransfer->setHasRedirect($campaign->hasRedirect());
            /** @var \FACTFinder\Data\Record $pushedProduct */
            foreach ($campaign->getPushedProducts() as $pushedProduct) {
                $this->recordConverter->setRecord($pushedProduct);
                $factFinderDataCampaignTransfer->addPushedProducts(
                    $this->recordConverter->convert()
                );
            }
            /** @var \FACTFinder\Data\AdvisorQuestion $activeQuestion */
            foreach ($campaign->getActiveQuestions() as $activeQuestion) {
                $this->advisorQuestionConverter->setAdvisorQuestion($activeQuestion);
                $factFinderDataCampaignTransfer->addActiveQuestions(
                    $this->advisorQuestionConverter->convert()
                );
            }
            /** @var \FACTFinder\Data\AdvisorQuestion $advisorTree */
            foreach ($campaign->getAdvisorTree() as $advisorTree) {
                $this->advisorQuestionConverter->setAdvisorQuestion($advisorTree);
                $factFinderDataCampaignTransfer->addAdvisorTree(
                    $this->advisorQuestionConverter->convert()
                );
            }

            $factFinderDataCampaignIteratorTransfer->addCampaigns($factFinderDataCampaignTransfer);
        }

        return $factFinderDataCampaignIteratorTransfer;
    }

    /**
     * @param \FACTFinder\Data\AfterSearchNavigation $afterSearchNavigation
     *
     * @return \Generated\Shared\Transfer\FactFinderDataAfterSearchNavigationTransfer
     */
    protected function convertAfterSearchNavigation(AfterSearchNavigation $afterSearchNavigation)
    {
        $factFinderDataAfterSearchNavigationTransfer = new FactFinderDataAfterSearchNavigationTransfer();
        $factFinderDataAfterSearchNavigationTransfer->setHasPreviewImages($afterSearchNavigation->hasPreviewImages());

        /** @var \FACTFinder\Data\FilterGroup $filterGroup */
        foreach ($afterSearchNavigation as $filterGroup) {
            $this->filterGroupConverter->setFilterGroup($filterGroup);
            $factFinderDataAfterSearchNavigationTransfer->addFilterGroups(
                $this->filterGroupConverter->convert()
            );
        }

        return $factFinderDataAfterSearchNavigationTransfer;
    }

    /**
     * @param \FACTFinder\Data\BreadCrumbTrail $breadCrumbTrail
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\FactFinderDataBreadCrumbTransfer[]
     */
    protected function convertBreadCrumbTrail(BreadCrumbTrail $breadCrumbTrail)
    {
        $breadCrumbs = new ArrayObject();
        /** @var \FACTFinder\Data\BreadCrumb $breadCrumb */
        foreach ($breadCrumbTrail as $breadCrumb) {
            $factFinderDataBreadCrumbTransfer = new FactFinderDataBreadCrumbTransfer();
            $factFinderDataBreadCrumbTransfer->setIsSearchBreadCrumb($breadCrumb->isSearchBreadCrumb());
            $factFinderDataBreadCrumbTransfer->setIsFilterBreadCrumb($breadCrumb->isFilterBreadCrumb());
            $factFinderDataBreadCrumbTransfer->setFieldName($breadCrumb->getFieldName());

            $this->itemConverter->setItem($breadCrumb);
            $factFinderDataBreadCrumbTransfer->setItem($this->itemConverter->convert());

            $breadCrumbs->append($factFinderDataBreadCrumbTransfer);
        }

        return $breadCrumbs;
    }

    /**
     * @param \FACTFinder\Data\Result $result
     *
     * @return \Generated\Shared\Transfer\FactFinderDataResultTransfer
     */
    protected function convertResult(Result $result)
    {
        $factFinderDataResultTransfer = new FactFinderDataResultTransfer();
        $factFinderDataResultTransfer->setFoundRecordsCount($result->getFoundRecordsCount());
        $factFinderDataResultTransfer->setFieldNames(FactFinderConstants::ITEM_FIELDS);
        /** @var \FACTFinder\Data\Record $record */
        foreach ($result as $record) {
            $this->recordConverter->setRecord($record);
            $factFinderDataResultTransfer->addRecords(
                $this->recordConverter->convert()
            );
        }

        return $factFinderDataResultTransfer;
    }

    /**
     * @param \FACTFinder\Data\ResultsPerPageOptions $resultsPerPageOptions
     *
     * @return \Generated\Shared\Transfer\FactFinderDataResultsPerPageOptionsTransfer
     */
    protected function convertResultsPerPageOptions(ResultsPerPageOptions $resultsPerPageOptions)
    {
        $factFinderDataResultsPerPageOptionsTransfer = new FactFinderDataResultsPerPageOptionsTransfer();

        $this->itemConverter->setItem($resultsPerPageOptions->getDefaultOption());
        $factFinderDataResultsPerPageOptionsTransfer->setDefaultOption(
            $this->itemConverter->convert()
        );
        $this->itemConverter->setItem($resultsPerPageOptions->getSelectedOption());
        $factFinderDataResultsPerPageOptionsTransfer->setSelectedOption(
            $this->itemConverter->convert()
        );
        /** @var \FACTFinder\Data\Item $resultsPerPageOption */
        foreach ($resultsPerPageOptions as $resultsPerPageOption) {
            $this->itemConverter->setItem($resultsPerPageOption);
            $factFinderDataResultsPerPageOptionsTransfer->addItems(
                $this->itemConverter->convert()
            );
        }

        return $factFinderDataResultsPerPageOptionsTransfer;
    }

    /**
     * @param \FACTFinder\Data\SingleWordSearchItem[] $singleWordSearch
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\FactFinderDataSingleWordSearchItemTransfer[]
     */
    protected function convertSingleWordSearch($singleWordSearch)
    {
        $singleWordSearchItems = new ArrayObject();
        foreach ($singleWordSearch as $singleWordSearchItem) {
            $factFinderDataSingleWordSearchItemTransfer = new FactFinderDataSingleWordSearchItemTransfer();
            /** @var \FACTFinder\Data\Record $previewRecord */
            foreach ($singleWordSearchItem->getPreviewRecords() as $previewRecord) {
                $this->recordConverter->setRecord($previewRecord);
                $factFinderDataSingleWordSearchItemTransfer->addPreviewRecords(
                    $this->recordConverter->convert()
                );
            }
            $factFinderDataSingleWordSearchItemTransfer->setSuggestQuery(
                $this->convertSuggestQuery($singleWordSearchItem)
            );

            $singleWordSearchItems->append($factFinderDataSingleWordSearchItemTransfer);
        }

        return $singleWordSearchItems;
    }

    /**
     * @param \FACTFinder\Data\SuggestQuery $suggestQuery
     *
     * @return \Generated\Shared\Transfer\FactFinderDataSuggestQueryTransfer
     */
    protected function convertSuggestQuery(SuggestQuery $suggestQuery)
    {
        $factFinderDataSuggestQueryTransfer = new FactFinderDataSuggestQueryTransfer();
        $factFinderDataSuggestQueryTransfer->setHitCount($suggestQuery->getHitCount());
        $factFinderDataSuggestQueryTransfer->setType($suggestQuery->getType());
        $factFinderDataSuggestQueryTransfer->setImageUrl($suggestQuery->getImageUrl());
        $factFinderDataSuggestQueryTransfer->setAttributes($suggestQuery->getAttributes());

        return $factFinderDataSuggestQueryTransfer;
    }

    /**
     * @param \FACTFinder\Data\Sorting $sorting
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\FactFinderDataItemTransfer[]
     */
    protected function convertSorting(Sorting $sorting)
    {
        $sortingItems = new ArrayObject();
        /** @var \FACTFinder\Data\Item $sortingItem */
        foreach ($sorting as $sortingItem) {
            $this->itemConverter->setItem($sortingItem);

            $sortingItems->append($this->itemConverter->convert());
        }

        return $sortingItems;
    }

}
