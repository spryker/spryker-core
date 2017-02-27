<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataFeed\Business\FeedExporter;

use Generated\Shared\Transfer\DataFeedConditionTransfer;
use Orm\Zed\Price\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageTableMap;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\DataFeed\Persistence\DataFeedQueryContainerInterface;

class PriceFeedExporter extends FeedExporterAbstract implements FeedExporterInterface
{

    /**
     * @param \Generated\Shared\Transfer\DataFeedConditionTransfer $dataFeedConditionTransfer
     *
     * @return array
     */
    public function getDataFeed(DataFeedConditionTransfer $dataFeedConditionTransfer)
    {
        $priceEntities = $this->queryContainer
            ->queryPriceDataFeedCollection($dataFeedConditionTransfer)
            ->find();

        return $this->convertEntityCollection($priceEntities);
    }

}
