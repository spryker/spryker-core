<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Persistence;

use Generated\Shared\Transfer\CurrencyCollectionTransfer;
use Generated\Shared\Transfer\CurrencyCriteriaTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;

interface CurrencyRepositoryInterface
{
    /**
     * @param string $isoCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer|null
     */
    public function findCurrencyByIsoCode(string $isoCode): ?CurrencyTransfer;

    /**
     * @param array<string> $isoCodes
     *
     * @return array<\Generated\Shared\Transfer\CurrencyTransfer>
     */
    public function getCurrencyTransfersByIsoCodes(array $isoCodes): array;

    /**
     * Result format:
     * [
     *     $idStore => [$currencyCode, ...],
     *     ...
     * ]
     *
     * @phpstan-return array<int, array<int, string>>
     *
     * @param array<int> $storeIds
     *
     * @return array<int, array<string>>
     */
    public function getCurrencyCodesGroupedByIdStore(array $storeIds): array;

    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer|null
     */
    public function findCurrencyById(int $id): ?CurrencyTransfer;

    /**
     * @param array<int> $storeIds
     *
     * @return array<int, string>
     */
    public function getStoreDefaultCurrencyCodes(array $storeIds): array;

    /**
     * @param \Generated\Shared\Transfer\CurrencyCriteriaTransfer $currencyCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CurrencyCollectionTransfer
     */
    public function getCurrencyCollection(CurrencyCriteriaTransfer $currencyCriteriaTransfer): CurrencyCollectionTransfer;
}
