<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ValidationResponseTransfer;

interface PriceProductMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     * @param mixed[] $initialData
     *
     * @return mixed[]
     */
    public function mapValidationResponseTransferToInitialDataErrors(
        ValidationResponseTransfer $validationResponseTransfer,
        array $initialData
    ): array;

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param mixed[] $data
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function mapDataToPriceProductTransfers(array $data, ArrayObject $priceProductTransfers): ArrayObject;
}
