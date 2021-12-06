<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Persistence;

use Generated\Shared\Transfer\StoreRelationTransfer;

interface DiscountRepositoryInterface
{
    /**
     * @param array<string> $codes
     *
     * @return array<string>
     */
    public function findVoucherCodesExceedingUsageLimit(array $codes): array;

    /**
     * @deprecated Will be removed in the next major without replacement.
     *
     * @return bool
     */
    public function hasPriorityField(): bool;

    /**
     * @param int $idDiscount
     *
     * @return array<\Generated\Shared\Transfer\MoneyValueTransfer>
     */
    public function getDiscountAmountCollectionForDiscount(int $idDiscount): array;

    /**
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getDiscountStoreRelations(int $idDiscount): StoreRelationTransfer;

    /**
     * @param int $idDiscount
     *
     * @return bool
     */
    public function discountExists(int $idDiscount): bool;

    /**
     * @param int $idDiscount
     *
     * @return bool
     */
    public function discountVoucherPoolExists(int $idDiscount): bool;
}
