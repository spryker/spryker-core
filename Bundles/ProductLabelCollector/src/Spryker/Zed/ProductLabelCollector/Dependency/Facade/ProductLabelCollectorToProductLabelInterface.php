<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelCollector\Dependency\Facade;

interface ProductLabelCollectorToProductLabelInterface
{
    /**
     * @return array<\Generated\Shared\Transfer\ProductLabelTransfer>
     */
    public function findAllLabels();
}
