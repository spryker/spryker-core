<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Persistence;

use SprykerFeature\Zed\SearchPage\Persistence\QueryExpander\SearchPageConfigQueryExpander;
use Generated\Zed\Ide\FactoryAutoCompletion\SearchPagePersistence;
use SprykerEngine\Zed\Kernel\Persistence\AbstractPersistenceDependencyContainer;

/**
 * @method SearchPagePersistence getFactory()
 */
class SearchPageDependencyContainer extends AbstractPersistenceDependencyContainer
{

    public function createSearchPageConfigQueryExpander()
    {
        return new SearchPageConfigQueryExpander();
    }

}
