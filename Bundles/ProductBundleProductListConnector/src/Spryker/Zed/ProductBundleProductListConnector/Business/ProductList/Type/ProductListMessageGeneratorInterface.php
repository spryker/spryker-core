<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type;

use Generated\Shared\Transfer\MessageTransfer;

interface ProductListMessageGeneratorInterface
{
    /**
     * @param string $value
     * @param int $idProductConcreteBundle
     * @param int[] $idsProductConcreteAssigned
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    public function generateMessageTransfer(string $value, int $idProductConcreteBundle, array $idsProductConcreteAssigned): MessageTransfer;
}
