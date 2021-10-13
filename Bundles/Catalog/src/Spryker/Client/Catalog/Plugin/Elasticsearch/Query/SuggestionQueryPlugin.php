<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Plugin\Elasticsearch\Query;

use Elastica\Query\MultiMatch;

class SuggestionQueryPlugin extends CatalogSearchQueryPlugin
{
    /**
     * @param array<string> $fields
     * @param string $searchString
     *
     * @return \Elastica\Query\MultiMatch
     */
    protected function createMultiMatchQuery(array $fields, $searchString): MultiMatch
    {
        return (new MultiMatch())
            ->setFields($fields)
            ->setQuery($searchString);
    }
}
