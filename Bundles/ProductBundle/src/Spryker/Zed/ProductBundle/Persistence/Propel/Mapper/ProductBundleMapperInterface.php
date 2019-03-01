<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductBundleTransfer;

interface ProductBundleMapperInterface
{
    /**
     * @param array $bundledProducts
     *
     * @return \Generated\Shared\Transfer\ProductBundleTransfer
     */
    public function mapProductBundleTransfer(array $bundledProducts): ProductBundleTransfer;
}
