<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataFeed\Business\FeedExporter;

use Generated\Shared\Transfer\DataFeedConditionTransfer;
use Spryker\Zed\DataFeed\Persistence\DataFeedQueryContainerInterface;

class ProductExporter implements FeedExporterInterface
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
     * @param \Generated\Shared\Transfer\DataFeedConditionTransfer $dataFeedConditionTransfer
     *
     * @return array
     */
    public function getDataFeed(DataFeedConditionTransfer $dataFeedConditionTransfer)
    {
        return $this->queryContainer
            ->queryDataFeedCollection($dataFeedConditionTransfer)
            ->find()
            ->toArray();
    }

}
