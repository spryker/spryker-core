<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Converter;

use FACTFinder\Adapter\Search as FFSearchAdapter;
use FACTFinder\Data\AdvisorQuestion;
use FACTFinder\Data\AfterSearchNavigation;
use FACTFinder\Data\ArticleNumberSearchStatus;
use FACTFinder\Data\BreadCrumbTrail;
use FACTFinder\Data\CampaignIterator;
use FACTFinder\Data\Item;
use FACTFinder\Data\Paging;
use FACTFinder\Data\Record;
use FACTFinder\Data\Result;
use FACTFinder\Data\ResultsPerPageOptions;
use FACTFinder\Data\Sorting;
use Generated\Shared\Transfer\FactFinderDataAdvisorQuestionTransfer;
use Generated\Shared\Transfer\FactFinderDataBreadCrumbTransfer;
use Generated\Shared\Transfer\FactFinderDataCampaignTransfer;
use Generated\Shared\Transfer\FactFinderDataFilterGroupTransfer;
use Generated\Shared\Transfer\FactFinderDataItemTransfer;
use Generated\Shared\Transfer\FactFinderDataRecordTransfer;
use Generated\Shared\Transfer\FactFinderSearchResponseTransfer;
use Spryker\Zed\FactFinder\Business\Api\Converter\Data\ItemConverter;
use Spryker\Zed\FactFinder\Business\Api\Converter\Data\RecordConverter;

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
     * @var \Spryker\Zed\FactFinder\Business\Api\Converter\Data\ItemConverter
     */
    protected $itemConverter;

    /**
     * @var \Spryker\Zed\FactFinder\Business\Api\Converter\Data\RecordConverter
     */
    protected $recordConverter;

    /**
     * @param \FACTFinder\Adapter\Search $searchAdapter
     * @param \Spryker\Zed\FactFinder\Business\Api\Converter\Data\ItemConverter $itemConverter
     * @param \Spryker\Zed\FactFinder\Business\Api\Converter\Data\RecordConverter $recordConverter
     */
    public function __construct(
        FFSearchAdapter $searchAdapter,
        ItemConverter $itemConverter,
        RecordConverter $recordConverter
    ) {
        $this->searchAdapter = $searchAdapter;
        $this->itemConverter = $itemConverter;
        $this->recordConverter = $recordConverter;
    }

    /**
     * @return \Generated\Shared\Transfer\FactFinderSearchResponseTransfer
     */
    public function convert()
    {
        $this->responseTransfer = new FactFinderSearchResponseTransfer();

        $this->convertCampaigns($this->searchAdapter->getCampaigns());
        $this->convertAfterSearchNavigation($this->searchAdapter->getAfterSearchNavigation());
        $this->convertBreadCrumbTrail($this->searchAdapter->getBreadCrumbTrail());
        $this->convertPaging($this->searchAdapter->getPaging());
        $this->convertResult($this->searchAdapter->getResult());
        $this->convertResultsPerPageOptions($this->searchAdapter->getResultsPerPageOptions());
        $this->convertSingleWordSearch($this->searchAdapter->getSingleWordSearch());
        $this->convertSorting($this->searchAdapter->getSorting());
//        $followSearchValue = $this->searchAdapter->getFollowSearchValue();

        return $this->responseTransfer;
    }

    /**
     * @param \FACTFinder\Data\CampaignIterator $campaigns
     */
    protected function convertCampaigns(CampaignIterator $campaigns)
    {
        /** @var \FACTFinder\Data\Campaign $campaign */
        foreach ($campaigns as $campaign) {
            $factFinderDataCampaignTransfer = new FactFinderDataCampaignTransfer();
            $factFinderDataCampaignTransfer->setName($campaign->getName());
            $factFinderDataCampaignTransfer->setCategory($campaign->getCategory());
            $factFinderDataCampaignTransfer->setRedirectUrl($campaign->getRedirectUrl());
            $factFinderDataCampaignTransfer->setFeedback($campaign->getFeedbackArray());
            $factFinderDataCampaignTransfer->setHasRedirect($campaign->hasRedirect());

            foreach ($campaign->getPushedProducts() as $pushedProduct) {
                $this->recordConverter->setRecord($pushedProduct);
                $factFinderDataCampaignTransfer->addPushedProducts(
                    $this->recordConverter->convert()
                );
            }
            foreach ($campaign->getActiveQuestions() as $activeQuestion) {
                $factFinderDataCampaignTransfer->addActiveQuestions(
                    $this->convertAdvisorQuestion($activeQuestion)
                );
            }
            foreach ($campaign->getAdvisorTree() as $advisorQuestion) {
                $factFinderDataCampaignTransfer->addAdvisorTree(
                    $this->convertAdvisorQuestion($advisorQuestion)
                );
            }

            $this->responseTransfer->addFactFinderDataCampaigns($factFinderDataCampaignTransfer);
        }
    }

    /**
     * @param \FACTFinder\Data\AfterSearchNavigation $afterSearchNavigation
     */
    protected function convertAfterSearchNavigation(AfterSearchNavigation $afterSearchNavigation)
    {
        /** @var \FACTFinder\Data\FilterGroup $filterGroup */
        foreach ($afterSearchNavigation as $filterGroup) {
            $factFinderDataFilterGroupTransfer = new FactFinderDataFilterGroupTransfer();
            $factFinderDataFilterGroupTransfer->setName($filterGroup->getName());
            $factFinderDataFilterGroupTransfer->setDetailedLinkCount($filterGroup->getDetailedLinkCount());
            $factFinderDataFilterGroupTransfer->setUnit($filterGroup->getUnit());
            $factFinderDataFilterGroupTransfer->setIsRegularStyle($filterGroup->isRegularStyle());
            $factFinderDataFilterGroupTransfer->setIsSliderStyle($filterGroup->isSliderStyle());
            $factFinderDataFilterGroupTransfer->setIsTreeStyle($filterGroup->isTreeStyle());
            $factFinderDataFilterGroupTransfer->setIsMultiSelectStyle($filterGroup->isMultiSelectStyle());
            $factFinderDataFilterGroupTransfer->setHasPreviewImages($filterGroup->hasPreviewImages());
            $factFinderDataFilterGroupTransfer->setHasSelectedItems($filterGroup->hasSelectedItems());
            $factFinderDataFilterGroupTransfer->setIsSingleHideUnselectedType($filterGroup->isSingleHideUnselectedType());
            $factFinderDataFilterGroupTransfer->setIsSingleShowUnselectedType($filterGroup->isSingleShowUnselectedType());
            $factFinderDataFilterGroupTransfer->setIsMultiSelectOrType($filterGroup->isMultiSelectOrType());
            $factFinderDataFilterGroupTransfer->setIsMultiSelectAndType($filterGroup->isMultiSelectAndType());
            $factFinderDataFilterGroupTransfer->setIsTextType($filterGroup->isTextType());
            $factFinderDataFilterGroupTransfer->setIsNumberType($filterGroup->isNumberType());

            $this->responseTransfer->addFactFinderDataFilterGroups($factFinderDataFilterGroupTransfer);
        }
    }

    /**
     * @param \FACTFinder\Data\BreadCrumbTrail $breadCrumbTrail
     */
    protected function convertBreadCrumbTrail(BreadCrumbTrail $breadCrumbTrail)
    {
        /** @var \FACTFinder\Data\BreadCrumb $breadCrumb */
        foreach ($breadCrumbTrail as $breadCrumb) {
            $factFinderDataBreadCrumbTransfer = new FactFinderDataBreadCrumbTransfer();
            $factFinderDataBreadCrumbTransfer->setIsSearchBreadCrumb($breadCrumb->isSearchBreadCrumb());
            $factFinderDataBreadCrumbTransfer->setIsFilterBreadCrumb($breadCrumb->isFilterBreadCrumb());
            $factFinderDataBreadCrumbTransfer->setFieldName($breadCrumb->getFieldName());

            $this->itemConverter->setItem($breadCrumb);
            $factFinderDataBreadCrumbTransfer->setFactFinderDataItem($this->itemConverter->convert());

            $this->responseTransfer->addFactFinderDataBreadCrumbs($factFinderDataBreadCrumbTransfer);
        }
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

    /**
     * @param \FACTFinder\Data\Record $record
     *
     * @return \Generated\Shared\Transfer\FactFinderDataRecordTransfer
     */
    protected function convertRecord(Record $record)
    {
        $factFinderDataRecordTransfer = new FactFinderDataRecordTransfer();

        return $factFinderDataRecordTransfer;
    }

    /**
     * @param \FACTFinder\Data\AdvisorQuestion $advisorQuestion
     *
     * @return \Generated\Shared\Transfer\FactFinderDataAdvisorQuestionTransfer
     */
    protected function convertAdvisorQuestion(AdvisorQuestion $advisorQuestion)
    {
        $factFinderDataAdvisorQuestionTransfer = new FactFinderDataAdvisorQuestionTransfer();

        return $factFinderDataAdvisorQuestionTransfer;
    }


}
