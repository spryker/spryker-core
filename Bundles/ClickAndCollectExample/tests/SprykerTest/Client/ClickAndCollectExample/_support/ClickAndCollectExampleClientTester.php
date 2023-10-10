<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Client\ClickAndCollectExample;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer;
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
 * @method \Spryker\Client\ClickAndCollectExample\ClickAndCollectExampleClientInterface getClient(?string $moduleName = null)
 *
 * @SuppressWarnings(\SprykerTest\Client\ClickAndCollectExample\PHPMD)
 */
class ClickAndCollectExampleClientTester extends Actor
{
    use _generated\ClickAndCollectExampleClientTesterActions;

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
    public function createProductOfferServicePointAvailabilityConditionsTransfer(
        array $productOfferServicePointAvailabilityRequestItemsSeedData = [],
        array $servicePointUuids = [self::SERVICE_POINT_UUID_1]
    ): ProductOfferServicePointAvailabilityConditionsTransfer {
        $productOfferServicePointAvailabilityConditionsTransfer = (new ProductOfferServicePointAvailabilityConditionsTransfer())
            ->setServicePointUuids($servicePointUuids);

        foreach ($productOfferServicePointAvailabilityRequestItemsSeedData as $productOfferServicePointAvailabilityRequestItemSeedData) {
            $productOfferServicePointAvailabilityConditionsTransfer->addProductOfferServicePointAvailabilityRequestItem(
                (new ProductOfferServicePointAvailabilityRequestItemTransfer())
                    ->fromArray($productOfferServicePointAvailabilityRequestItemSeedData),
            );
        }

        return $productOfferServicePointAvailabilityConditionsTransfer;
    }
}
