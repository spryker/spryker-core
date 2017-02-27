<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataFeed\Business\FeedExporter;

use Generated\Shared\Transfer\DataFeedConditionTransfer;

class StockFeedExporter extends FeedExporterAbstract implements FeedExporterInterface
{

    /**
     * @param \Generated\Shared\Transfer\DataFeedConditionTransfer $dataFeedConditionTransfer
     *
     * @return array
     */
    public function getDataFeed(DataFeedConditionTransfer $dataFeedConditionTransfer)
    {
        $stockEntities = $this->queryContainer
            ->queryStockDataFeedCollection($dataFeedConditionTransfer)
            ->find();

        return $this->convertEntityCollection($stockEntities);
    }

}
