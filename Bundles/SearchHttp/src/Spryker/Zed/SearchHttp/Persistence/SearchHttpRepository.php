<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchHttp\Persistence;

use Generated\Shared\Transfer\SearchHttpConfigCriteriaTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Formatter\ObjectFormatter;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SearchHttp\Persistence\SearchHttpPersistenceFactory getFactory()
 */
class SearchHttpRepository extends AbstractRepository implements SearchHttpRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SearchHttpConfigCriteriaTransfer $searchHttpConfigCriteriaTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection
     */
    public function getFilteredSearchHttpEntityTransfers(
        SearchHttpConfigCriteriaTransfer $searchHttpConfigCriteriaTransfer
    ): ObjectCollection {
        $searchHttpConfigQuery = $this->getFactory()->createSearchHttpPropelQuery();

        if ($searchHttpConfigCriteriaTransfer->getIds()) {
            $searchHttpConfigQuery->filterByPrimaryKeys($searchHttpConfigCriteriaTransfer->getIds());
        }

        if ($searchHttpConfigCriteriaTransfer->getFilter()) {
            $searchHttpConfigQuery = $this->buildQueryFromCriteria(
                $searchHttpConfigQuery,
                $searchHttpConfigCriteriaTransfer->getFilter(),
            )->setFormatter(ObjectFormatter::class);
        }

        return $searchHttpConfigQuery->find();
    }
}
