<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication\Transfer;

interface SortedProductSearchTransferListGeneratorInterface
{

    /**
     * @param array $filterList
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer[]
     */
    public function createList(array $filterList);

}
