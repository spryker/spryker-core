<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductFeed\Business\Exporter;

use Generated\Shared\Transfer\ProductFeedConditionTransfer;

interface ProductExporterInterface
{

    /**
     * @param \Generated\Shared\Transfer\ProductFeedConditionTransfer $productFeedConditionTransfer
     *
     * @return array
     */
    public function getProductFeed(ProductFeedConditionTransfer $productFeedConditionTransfer);

}
