<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductOfferServicePointAvailabilityCalculatorStorage;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductOfferServicePointAvailabilityCalculatorStorageClientTester extends Actor
{
    use _generated\ProductOfferServicePointAvailabilityCalculatorStorageClientTesterActions;

    /**
     * @var string
     */
    public const SERVICE_POINT_UUID_1 = 'uuid-1';

    /**
     * @var string
     */
    public const SERVICE_POINT_UUID_2 = 'uuid-2';

    /**
     * @param array<list<string, mixed>> $productOfferServicePointAvailabilityRequestItemsSeedData
     * @param array<string> $servicePointUuids
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer
     */
    public function createProductOfferServicePointAvailabilityCriteriaTransfer(
        array $productOfferServicePointAvailabilityRequestItemsSeedData = [],
        array $servicePointUuids = [self::SERVICE_POINT_UUID_1, self::SERVICE_POINT_UUID_2]
    ): ProductOfferServicePointAvailabilityCriteriaTransfer {
        $productOfferServicePointAvailabilityConditionsTransfer = (new ProductOfferServicePointAvailabilityConditionsTransfer())
            ->setServicePointUuids($servicePointUuids);

        foreach ($productOfferServicePointAvailabilityRequestItemsSeedData as $productOfferServicePointAvailabilityRequestItemSeedData) {
            $productOfferServicePointAvailabilityConditionsTransfer->addProductOfferServicePointAvailabilityRequestItem(
                (new ProductOfferServicePointAvailabilityRequestItemTransfer())
                    ->fromArray($productOfferServicePointAvailabilityRequestItemSeedData),
            );
        }

        return (new ProductOfferServicePointAvailabilityCriteriaTransfer())
            ->setProductOfferServicePointAvailabilityConditions($productOfferServicePointAvailabilityConditionsTransfer);
    }
}
