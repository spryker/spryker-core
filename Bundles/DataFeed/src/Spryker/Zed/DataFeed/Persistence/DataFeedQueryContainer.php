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
    public function queryProductDataFeedCollection(DataFeedConditionTransfer $dataFeedConditionTransfer)
    {
        return $this->getFactory()
            ->createProductQueryBuilder()
            ->getDataFeed($dataFeedConditionTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\DataFeedConditionTransfer $dataFeedConditionTransfer
     *
     * @return \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    public function queryCategoryDataFeedCollection(DataFeedConditionTransfer $dataFeedConditionTransfer)
    {
        return $this->getFactory()
            ->createCategoryQueryBuilder()
            ->getDataFeed($dataFeedConditionTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\DataFeedConditionTransfer $dataFeedConditionTransfer
     *
     * @return \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface
     */
    public function queryStockDataFeedCollection(DataFeedConditionTransfer $dataFeedConditionTransfer)
    {
        return $this->getFactory()
            ->createStockQueryBuilder()
            ->getDataFeed($dataFeedConditionTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\DataFeedConditionTransfer $dataFeedConditionTransfer
     *
     * @return \Spryker\Zed\Price\Persistence\PriceQueryContainerInterface
     */
    public function queryPriceDataFeedCollection(DataFeedConditionTransfer $dataFeedConditionTransfer)
    {
        return $this->getFactory()
            ->createPriceQueryBuilder()
            ->getDataFeed($dataFeedConditionTransfer);
    }

}
