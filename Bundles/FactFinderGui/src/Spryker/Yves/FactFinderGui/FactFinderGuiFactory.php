<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\FactFinderGui;

use Generated\Shared\Transfer\FactFinderRecommendationRequestTransfer;
use Generated\Shared\Transfer\FactFinderSearchRequestTransfer;
use Generated\Shared\Transfer\FactFinderSuggestRequestTransfer;
use Generated\Shared\Transfer\FactFinderTrackingRequestTransfer;
use Spryker\Client\FactFinder\FactFinderClient;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\FactFinder\FactFinderClientInterface getClient()
 */
class FactFinderGuiFactory extends AbstractFactory
{

    /**
     * @return FactFinderClient
     */
    public function getFactFinderClient()
    {
        return $this->getProvidedDependency(FactFinderGuiDependencyProvider::FACT_FINDER_CLIENT);
    }

    /**
     * @return FactFinderSearchRequestTransfer
     */
    public function createFactFinderSearchRequestTransfer()
    {
        return new FactFinderSearchRequestTransfer();
    }

    /**
     * @return FactFinderSuggestRequestTransfer
     */
    public function createFactFinderSuggestRequestTransfer()
    {
        return new FactFinderSuggestRequestTransfer();
    }

    /**
     * @return FactFinderRecommendationRequestTransfer
     */
    public function createFactFinderRecommendationRequestTransfer()
    {
        return new FactFinderRecommendationRequestTransfer();
    }

    /**
     * @return FactFinderTrackingRequestTransfer
     */
    public function createFactFinderTrackingRequestTransfer()
    {
        return new FactFinderTrackingRequestTransfer();
    }

}
