<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SearchElasticsearch\Index;

interface IndexNameResolverInterface
{
    /**
     * @param string $indexName
     *
     * @return string
     */
    public function resolve(string $indexName): string;
}
