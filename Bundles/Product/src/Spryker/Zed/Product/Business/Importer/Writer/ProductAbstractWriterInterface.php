<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Importer\Writer;

use Generated\Shared\Transfer\ProductAbstractTransfer;

interface ProductAbstractWriterInterface
{

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $product
     *
     * @return bool
     */
    public function writeProductAbstract(ProductAbstractTransfer $product);

}
