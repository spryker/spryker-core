<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Business\Mapper;

use Propel\Runtime\Collection\ArrayCollection;

interface TransferMapperInterface
{

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    public function toTransfer(array $data);

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract[]|\Propel\Runtime\Collection\ArrayCollection $productEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer[]
     */
    public function toTransferCollection(ArrayCollection $productEntityCollection);

}
