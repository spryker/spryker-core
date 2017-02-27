<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataFeed\Business;

use Generated\Shared\Transfer\DataFeedConditionTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\DataFeed\Business\DataFeedBusinessFactory getFactory()
 */
class DataFeedFacade extends AbstractFacade implements DataFeedFacadeInterface
{

    /**
     * Specification:
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataFeedConditionTransfer $dataFeedConditionTransfer
     *
     * @return array
     */
    public function getProductDataFeed(DataFeedConditionTransfer $dataFeedConditionTransfer)
    {
        return $this->getFactory()
            ->createProductFeedExporter()
            ->getDataFeed($dataFeedConditionTransfer);
    }

    /**
     * Specification:
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataFeedConditionTransfer $dataFeedConditionTransfer
     *
     * @return array
     */
    public function getCategoryDataFeed(DataFeedConditionTransfer $dataFeedConditionTransfer)
    {
        return $this->getFactory()
            ->createCategoryFeedExporter()
            ->getDataFeed($dataFeedConditionTransfer);
    }

    /**
     * Specification:
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataFeedConditionTransfer $dataFeedConditionTransfer
     *
     * @return array
     */
    public function getPriceDataFeed(DataFeedConditionTransfer $dataFeedConditionTransfer)
    {
        return $this->getFactory()
            ->createPriceFeedExporter()
            ->getDataFeed($dataFeedConditionTransfer);
    }

    /**
     * Specification:
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataFeedConditionTransfer $dataFeedConditionTransfer
     *
     * @return array
     */
    public function getStockDataFeed(DataFeedConditionTransfer $dataFeedConditionTransfer)
    {
        return $this->getFactory()
            ->createStockFeedExporter()
            ->getDataFeed($dataFeedConditionTransfer);
    }

}
