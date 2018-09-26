<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Mapper;

use Generated\Shared\Transfer\ProductPageSearchTransfer;

interface ProductPageSearchMapperInterface
{
    /**
     * @param array $productAbstractLocalizedData
     *
     * @return \Generated\Shared\Transfer\ProductPageSearchTransfer
     */
    public function mapToProductPageSearchTransfer(array $productAbstractLocalizedData);

    /**
     * @param string $data
     *
     * @return \Generated\Shared\Transfer\ProductPageSearchTransfer
     */
    public function mapToProductPageSearchTransferFromJson(string $data);

    /**
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productPageSearchTransfer
     *
     * @return array
     */
    public function mapToSearchData(ProductPageSearchTransfer $productPageSearchTransfer);
}
