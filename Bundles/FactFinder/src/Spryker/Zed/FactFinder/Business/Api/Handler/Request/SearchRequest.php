<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Handler\Request;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\FactFinder\Business\Api\ApiConstants;

class SearchRequest extends AbstractRequest implements RequestInterface
{
    
    const TRANSACTION_TYPE = ApiConstants::TRANSACTION_TYPE_SEARCH;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\FactFinderSearchResponseTransfer
     */
    public function request(QuoteTransfer $quoteTransfer)
    {
        $searchRequestTransfer = $quoteTransfer->getFactFinderSearchRequest();

        // @todo @Artem : check do we need send request? 
        // $request = mapper->map($searchRequestTransfer);

        $searchAdapter = $this->ffConnector->createSearchAdapter();
        // @todo check

        $error = $searchAdapter->getError();
        $status = $searchAdapter->getStatus();

        $campaigns = $searchAdapter->getCampaigns();
        if ($campaigns->hasRedirect()) {
            //throw new RedirectException($campaigns->getRedirectUrl());
            $redirectUrl = $campaigns->getRedirectUrl();
        }

        $error = $searchAdapter->getError();
        $afterSerchNavigation = $searchAdapter->getAfterSearchNavigation();
        $error = $searchAdapter->getError();
        $articleNumberStatus = $searchAdapter->getArticleNumberStatus();
        $error = $searchAdapter->getError();
        $breadCrumbTrail = $searchAdapter->getBreadCrumbTrail();
        $error = $searchAdapter->getError();
        $paging = $searchAdapter->getPaging();
        $error = $searchAdapter->getError();
        $result = $searchAdapter->getResult();
        $error = $searchAdapter->getError();
        $resultsPerPageOptions = $searchAdapter->getResultsPerPageOptions();
        $error = $searchAdapter->getError();
        $singleWordSearch = $searchAdapter->getSingleWordSearch();
        $error = $searchAdapter->getError();
        $sorting = $searchAdapter->getSorting();

        try {
            $followSearchValue = $searchAdapter->getFollowSearchValue();
        } catch (\Exception $e) {
            $tt = 11;
        }
        $error = $searchAdapter->getError();

        $this->logInfo($quoteTransfer, $searchAdapter);
        
        // convert to FactFinderSearchResponseTransfer
        $responseTransfer = $this->converterFactory
            ->createSearchResponseConverter($searchAdapter)
            ->convert();

        return $responseTransfer;
    }

}
