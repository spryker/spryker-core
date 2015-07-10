<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\UiExample\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\UiExample\Persistence\Propel\SpyUiExampleQuery;

class UiExampleQueryContainer extends AbstractQueryContainer
{

    /**
     * @return mixed
     */
    public function queryUiExample()
    {
        return SpyUiExampleQuery::create();
    }

}
