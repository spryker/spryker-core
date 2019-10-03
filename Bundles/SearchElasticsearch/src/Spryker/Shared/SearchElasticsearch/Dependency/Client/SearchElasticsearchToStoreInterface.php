<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SearchElasticsearch\Dependency\Client;

interface SearchElasticsearchToStoreInterface
{
    /**
     * @return string
     */
    public function getStoreName();

    /**
     * @return string
     */
    public function getCurrentLocale();
}
