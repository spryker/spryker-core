<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch;

use Spryker\Client\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\SearchElasticsearch\SearchElasticsearchConfig getSharedConfig()
 */
class SearchElasticsearchConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getClientConfig(): array
    {
        return $this->getSharedConfig()->getClientConfig();
    }

    /**
     * @return array
     */
    public function getIndexNameMap(): array
    {
        return $this->getSharedConfig()->getIndexNameMap();
    }
}
