<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\ApplicabilityChecker;

use Generated\Shared\Transfer\SearchContextTransfer;

interface QueryApplicabilityCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return bool
     */
    public function isQueryApplicable(SearchContextTransfer $searchContextTransfer): bool;
}
