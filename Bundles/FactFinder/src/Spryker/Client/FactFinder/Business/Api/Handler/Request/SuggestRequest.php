<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder\Business\Api\Handler\Request;

use FACTFinder\Util\Parameters;
use Generated\Shared\Transfer\FactFinderSuggestRequestTransfer;
use Spryker\Client\FactFinder\Business\Api\ApiConstants;

class SuggestRequest extends AbstractRequest implements SuggestRequestInterface
{

    const TRANSACTION_TYPE = ApiConstants::TRANSACTION_TYPE_SUGGEST;

    /**
     * @param \Generated\Shared\Transfer\FactFinderSuggestRequestTransfer $factFinderSuggestRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FactFinderSuggestResponseTransfer
     */
    public function request(FactFinderSuggestRequestTransfer $factFinderSuggestRequestTransfer)
    {
        $requestParameters = new Parameters();
        $requestParameters->setAll($factFinderSuggestRequestTransfer->toArray());
        $this->factFinderConnector->setRequestParameters($requestParameters);

        $suggestAdapter = $this->factFinderConnector->createSuggestAdapter();

        $responseTransfer = $this->converterFactory
            ->createSuggestResponseConverter($suggestAdapter)
            ->convert();

        return $responseTransfer;
    }

}
