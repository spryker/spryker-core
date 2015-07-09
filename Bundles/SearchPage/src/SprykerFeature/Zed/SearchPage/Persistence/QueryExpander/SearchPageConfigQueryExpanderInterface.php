<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Persistence\QueryExpander;

use Propel\Runtime\ActiveQuery\ModelCriteria;

interface SearchPageConfigQueryExpanderInterface
{

    /**
     * @param ModelCriteria $expandableQuery
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery);

}
