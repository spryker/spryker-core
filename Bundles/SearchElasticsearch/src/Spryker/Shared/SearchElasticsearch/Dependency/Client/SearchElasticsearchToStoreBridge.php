<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SearchElasticsearch\Dependency\Client;

class SearchElasticsearchToStoreBridge implements SearchElasticsearchToStoreInterface
{
    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct($store)
    {
        $this->store = $store;
    }

    /**
     * @return string
     */
    public function getStoreName()
    {
        return $this->store->getStoreName();
    }

    /**
     * @return string
     */
    public function getCurrentLocale()
    {
        return $this->store->getCurrentLocale();
    }
}
