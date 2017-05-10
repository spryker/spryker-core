<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder;

use Generated\Shared\Transfer\FactFinderRecommendationRequestTransfer;
use Generated\Shared\Transfer\FactFinderSearchRequestTransfer;
use Generated\Shared\Transfer\FactFinderSuggestRequestTransfer;
use Generated\Shared\Transfer\FactFinderTrackingRequestTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\FactFinder\FactFinderFactory getFactory()
 */
class FactFinderClient extends AbstractClient implements FactFinderClientInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\FactFinderSearchRequestTransfer $factFinderSearchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FactFinderSearchResponseTransfer
     */
    public function search(FactFinderSearchRequestTransfer $factFinderSearchRequestTransfer)
    {
        $ffSearchResponseTransfer = $this
            ->getFactory()
            ->createSearchRequest()
            ->request($factFinderSearchRequestTransfer);

        return $ffSearchResponseTransfer;
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\FactFinderTrackingRequestTransfer $factFinderTrackingRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FactFinderTrackingResponseTransfer
     */
    public function track(FactFinderTrackingRequestTransfer $factFinderTrackingRequestTransfer)
    {
        $factFinderTrackingResponseTransfer = $this->getFactory()
            ->createTrackingRequest()
            ->request($factFinderTrackingRequestTransfer);

        return $factFinderTrackingResponseTransfer;
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\FactFinderSuggestRequestTransfer $factFinderSuggestRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FactFinderSuggestResponseTransfer
     */
    public function getSuggestions(FactFinderSuggestRequestTransfer $factFinderSuggestRequestTransfer)
    {
        $ffSuggestResponseTransfer = $this
            ->getFactory()
            ->createSuggestRequest()
            ->request($factFinderSuggestRequestTransfer);

        return $ffSuggestResponseTransfer;
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\FactFinderRecommendationRequestTransfer $factFinderRecommendationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FactFinderRecommendationResponseTransfer
     */
    public function getRecommendations(FactFinderRecommendationRequestTransfer $factFinderRecommendationRequestTransfer)
    {
        $factFinderRecommendationResponseTransfer = $this
            ->getFactory()
            ->createRecommendationsRequest()
            ->request($factFinderRecommendationRequestTransfer);

        return $factFinderRecommendationResponseTransfer;
    }

    /**
     * @api
     *
     * @return \Spryker\Client\Session\SessionClientInterface
     */
    public function getSession()
    {
        return $this->getFactory()
            ->getSession();
    }

}
