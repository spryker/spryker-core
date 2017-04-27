<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder\Business\Api\Handler\Request;

use Generated\Shared\Transfer\FactFinderSimilarRecordsRequestTransfer;

interface SimilarRecordsRequestInterface
{

    /**
     * @param \Generated\Shared\Transfer\FactFinderSimilarRecordsRequestTransfer $factFinderSimilarRecordsRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FactFinderSimilarRecordsResponseTransfer
     */
    public function request(FactFinderSimilarRecordsRequestTransfer $factFinderSimilarRecordsRequestTransfer);

}
