<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\ApplicabilityChecker;

use Spryker\Client\SearchHttp\Reader\ConfigReaderInterface;

class QueryApplicabilityChecker implements QueryApplicabilityCheckerInterface
{
    /**
     * @var \Spryker\Client\SearchHttp\Reader\ConfigReaderInterface
     */
    protected ConfigReaderInterface $configReader;

    /**
     * @param \Spryker\Client\SearchHttp\Reader\ConfigReaderInterface $configReader
     */
    public function __construct(ConfigReaderInterface $configReader)
    {
        $this->configReader = $configReader;
    }

    /**
     * @return bool
     */
    public function isQueryApplicable(): bool
    {
        return $this->configReader
                ->getSearchHttpConfigCollectionForCurrentStore()
                ->getSearchHttpConfigs()->count() > 0;
    }
}
