<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataFeed\Persistence\QueryBuilder;

use Generated\Shared\Transfer\DataFeedConditionTransfer;

interface QueryBuilderInterface
{

    /**
     * @param \Generated\Shared\Transfer\DataFeedConditionTransfer $productFeedConditionTransfer
     *
     * @return array
     */
    public function getDataFeed(DataFeedConditionTransfer $productFeedConditionTransfer);

}
