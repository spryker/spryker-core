<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\PriceType;

use Generated\Shared\Transfer\PriceTypeTransfer;

interface PriceProductTypeReaderInterface
{
    /**
     * @return \Generated\Shared\Transfer\PriceTypeTransfer[]
     */
    public function getPriceTypes();

    /**
     * @param string|null $priceType
     *
     * @return string
     */
    public function handleDefaultPriceType($priceType = null);

    /**
     * @param string $priceTypeName
     *
     * @throws \Exception
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceType
     */
    public function getPriceTypeByName($priceTypeName);

    /**
     * @param string $priceTypeName
     *
     * @return bool
     */
    public function hasPriceType($priceTypeName);

    /**
     * @param string $priceTypeName
     *
     * @return \Generated\Shared\Transfer\PriceTypeTransfer|null
     */
    public function findPriceTypeByName(string $priceTypeName): ?PriceTypeTransfer;
}
