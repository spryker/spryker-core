<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductFeed\Business\Exporter;

use Generated\Shared\Transfer\ProductFeedConditionTransfer;
use Spryker\Zed\ProductFeed\Persistence\ProductFeedQueryContainerInterface;

class ProductExporter implements ProductExporterInterface
{

    /**
     * @var \Spryker\Zed\ProductFeed\Persistence\ProductFeedQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * ProductExporter constructor.
     *
     * @param \Spryker\Zed\ProductFeed\Persistence\ProductFeedQueryContainerInterface $queryContainer
     */
    public function __construct(ProductFeedQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductFeedConditionTransfer $productFeedConditionTransfer
     *
     * @return array
     */
    public function getProductFeed(ProductFeedConditionTransfer $productFeedConditionTransfer)
    {
        return $this->queryContainer
            ->queryProductFeedCollection($productFeedConditionTransfer)
            ->find()
            ->toArray();
    }

}
