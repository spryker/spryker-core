<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\ApplicabilityChecker;

use Spryker\Client\SearchHttp\Reader\ConfigReaderInterface;

class QueryApplicabilityChecker implements QueryApplicabilityCheckerInterface
{
    /**
     * @param \Spryker\Client\SearchHttp\Reader\ConfigReaderInterface $configReader
     */
    public function __construct(protected ConfigReaderInterface $configReader)
    {
    }

    /**
     * @return bool
     */
    public function isQueryApplicable(): bool
    {
        return $this->configReader
                ->getSearchHttpConfigCollectionForCurrentStore()
                ->getSearchHttpConfigs()
                ->count() > 0;
    }
}
