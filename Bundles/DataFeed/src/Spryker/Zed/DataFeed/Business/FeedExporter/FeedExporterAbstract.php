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

abstract class FeedExporterAbstract
{

    /**
     * @var \Spryker\Zed\DataFeed\Persistence\DataFeedQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * ProductExporter constructor.
     *
     * @param \Spryker\Zed\DataFeed\Persistence\DataFeedQueryContainerInterface $queryContainer
     */
    public function __construct(DataFeedQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param ObjectCollection $entityCollection
     *
     * @return array
     */
    protected function convertEntityCollection(ObjectCollection $entityCollection)
    {
        return $entityCollection->toArray();
    }

}
