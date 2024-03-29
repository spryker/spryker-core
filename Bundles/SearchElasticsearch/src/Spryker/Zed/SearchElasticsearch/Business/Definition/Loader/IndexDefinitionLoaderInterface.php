<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Definition\Loader;

interface IndexDefinitionLoaderInterface
{
    /**
     * @param string|null $storeName
     *
     * @return array
     */
    public function load(?string $storeName = null): array;
}
