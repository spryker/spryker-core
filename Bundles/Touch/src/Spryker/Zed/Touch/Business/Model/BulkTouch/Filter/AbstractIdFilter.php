<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Business\Model\BulkTouch\Filter;

use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;

abstract class AbstractIdFilter implements FilterInterface
{
    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @param \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface $touchQueryContainer
     */
    public function __construct(TouchQueryContainerInterface $touchQueryContainer)
    {
        $this->touchQueryContainer = $touchQueryContainer;
    }

    /**
     * @param string $itemType
     * @param array $itemIdChunk
     *
     * @return array
     */
    protected function getIdCollection(string $itemType, array $itemIdChunk)
    {
        $touchQuery = $this->touchQueryContainer->queryTouchEntriesByItemTypeAndItemIds($itemType, $itemIdChunk);

        return $touchQuery->select([SpyTouchTableMap::COL_ITEM_ID])->find()->toArray();
    }
}
