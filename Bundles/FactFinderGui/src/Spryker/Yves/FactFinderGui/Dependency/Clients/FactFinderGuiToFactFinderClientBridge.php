<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\FactFinderGui\Dependency\Clients;

use Generated\Shared\Transfer\FactFinderRecommendationRequestTransfer;
use Generated\Shared\Transfer\FactFinderSearchRequestTransfer;
use Generated\Shared\Transfer\FactFinderSuggestRequestTransfer;
use Generated\Shared\Transfer\FactFinderTrackingRequestTransfer;

class FactFinderGuiToFactFinderClientBridge implements FactFinderGuiToFactFinderClientInterface
{

    /**
     * @var \Spryker\Client\FactFinder\FactFinderClientInterface
     */
    protected $factFinderClient;

    /**
     * FactFinderGuiToFactFinderClientBridge constructor.
     *
     * @param \Spryker\Client\FactFinder\FactFinderClientInterface $factFinderClient
     */
    public function __construct($factFinderClient)
    {
        $this->factFinderClient = $factFinderClient;
    }

    /**
     * @param \Generated\Shared\Transfer\FactFinderSearchRequestTransfer $factFinderSearchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FactFinderSearchResponseTransfer
     */
    public function search(FactFinderSearchRequestTransfer $factFinderSearchRequestTransfer)
    {
        return $this->factFinderClient
            ->search($factFinderSearchRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FactFinderRecommendationRequestTransfer $factFinderRecommendationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FactFinderRecommendationsResponseTransfer
     */
    public function getRecommendations(FactFinderRecommendationRequestTransfer $factFinderRecommendationRequestTransfer)
    {
        return $this->factFinderClient
            ->getRecommendations($factFinderRecommendationRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FactFinderSuggestRequestTransfer $factFinderSuggestRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FactFinderSuggestResponseTransfer
     */
    public function getSuggestions(FactFinderSuggestRequestTransfer $factFinderSuggestRequestTransfer)
    {
        return $this->factFinderClient
            ->getSuggestions($factFinderSuggestRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FactFinderTrackingRequestTransfer $factFinderTrackingRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FactFinderTrackingResponseTransfer
     */
    public function track(FactFinderTrackingRequestTransfer $factFinderTrackingRequestTransfer)
    {
        return $this->factFinderClient
            ->track($factFinderTrackingRequestTransfer);
    }

}
