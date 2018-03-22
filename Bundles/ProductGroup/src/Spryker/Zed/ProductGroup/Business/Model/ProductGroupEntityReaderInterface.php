<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroup\Business\Model;

use Generated\Shared\Transfer\ProductGroupTransfer;

interface ProductGroupEntityReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @throws \Spryker\Zed\ProductGroup\Business\Exception\ProductGroupNotFoundException
     *
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductGroup
     */
    public function findProductGroupEntity(ProductGroupTransfer $productGroupTransfer);
}
