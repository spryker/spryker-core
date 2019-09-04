<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Query;

/**
 * @deprecated Use `\Spryker\Client\SearchElasticsearch\Query\NestedQueryInterface` instead.
 */
interface NestedQueryInterface
{
    /**
     * @return \Elastica\Query\Nested
     */
    public function createNestedQuery();
}
