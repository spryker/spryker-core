<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceType;

use Generated\Shared\Transfer\PriceTypeTransfer;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface;

class PriceTypeFinder implements PriceTypeFinderInterface
{
    /**
     * @var array
     */
    protected $priceTypeCache = [];

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface $priceProductFacade
     */
    public function __construct(
        PriceProductScheduleToPriceProductFacadeInterface $priceProductFacade
    ) {
        $this->priceProductFacade = $priceProductFacade;
    }

    /**
     * @param string $priceTypeName
     *
     * @return \Generated\Shared\Transfer\PriceTypeTransfer|null
     */
    public function findPriceTypeByName(string $priceTypeName): ?PriceTypeTransfer
    {
        if (isset($this->priceTypeCache[$priceTypeName])) {
            return $this->priceTypeCache[$priceTypeName];
        }

        $priceTypeTransfer = $this->priceProductFacade->findPriceTypeByName($priceTypeName);

        if ($priceTypeTransfer === null) {
            return null;
        }

        $this->priceTypeCache[$priceTypeName] = $priceTypeTransfer;

        return $this->priceTypeCache[$priceTypeName];
    }
}
