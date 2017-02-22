<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataFeed\Persistence;

use Generated\Shared\Transfer\DataFeedConditionTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\DataFeed\Persistence\DataFeedPersistenceFactory getFactory()
 */
class DataFeedQueryContainer extends AbstractQueryContainer implements DataFeedQueryContainerInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\DataFeedConditionTransfer $dataFeedConditionTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryDataFeedCollection(DataFeedConditionTransfer $dataFeedConditionTransfer)
    {
        return $this->getFactory()
            ->createProductQueryBuilder()
            ->getDataFeed($dataFeedConditionTransfer);
    }

}
