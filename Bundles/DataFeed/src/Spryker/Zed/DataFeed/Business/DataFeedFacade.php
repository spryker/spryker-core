<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataFeed\Business;

use Generated\Shared\Transfer\DataFeedConditionTransfer;
use Generated\Shared\Transfer\ProductFeedConditionTransfer;
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
            ->createProductExporter()
            ->getDataFeed($dataFeedConditionTransfer);
    }

}
