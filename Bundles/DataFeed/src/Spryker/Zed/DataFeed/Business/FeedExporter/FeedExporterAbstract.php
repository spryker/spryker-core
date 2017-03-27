<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataFeed\Business\FeedExporter;

use Generated\Shared\Transfer\DataFeedConditionTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\DataFeed\Persistence\DataFeedPager;
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
     * @param ModelCriteria $query
     * @param DataFeedConditionTransfer $dataFeedConditionTransfer
     *
     * @return array
     */
    public function getResults(ModelCriteria $query, DataFeedConditionTransfer $dataFeedConditionTransfer)
    {
        $pager = new DataFeedPager($query, $dataFeedConditionTransfer);
        $entitiesArray = $pager->getResults();

        return $entitiesArray;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $entityCollection
     *
     * @return array
     */
    protected function convertEntityCollection(ObjectCollection $entityCollection)
    {
        return $entityCollection->toArray();
    }

}
