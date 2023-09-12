<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\ServiceCollectionTransfer;

interface ServiceDataProviderInterface
{
    /**
     * @param string $searchTerm
     *
     * @return list<array<string, mixed>>
     */
    public function getServicePointSelectOptions(string $searchTerm): array;

    /**
     * @param int $idServicePoint
     *
     * @return list<array<string, string>>
     */
    public function getServiceSelectOptions(int $idServicePoint): array;

    /**
     * @param int $idServicePoint
     *
     * @return \Generated\Shared\Transfer\ServiceCollectionTransfer
     */
    public function getServiceCollectionByIdServicePoint(int $idServicePoint): ServiceCollectionTransfer;

    /**
     * @param int $idServicePoint
     *
     * @return array<string, string>
     */
    public function getServiceChoicesByIdServicePoint(int $idServicePoint): array;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer> $serviceTransfers
     *
     * @return array<string, int>
     */
    public function getServicePointChoicesByServices(ArrayObject $serviceTransfers): array;

    /**
     * @param int $idServicePoint
     *
     * @return array<string, int>
     */
    public function getServicePointChoicesByIdServicePoint(int $idServicePoint): array;
}
