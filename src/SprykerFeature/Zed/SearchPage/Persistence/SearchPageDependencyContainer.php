<?php

namespace SprykerFeature\Zed\SearchPage\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\SearchPagePersistence;
use SprykerEngine\Zed\Kernel\Persistence\AbstractDependencyContainer;

/**
 * @method SearchPagePersistence getFactory()
 */
class SearchPageDependencyContainer extends AbstractDependencyContainer
{

    public function createSearchPageConfigQueryExpander()
    {
        return $this->getFactory()->createQueryExpanderSearchPageConfigQueryExpander();
    }
}
