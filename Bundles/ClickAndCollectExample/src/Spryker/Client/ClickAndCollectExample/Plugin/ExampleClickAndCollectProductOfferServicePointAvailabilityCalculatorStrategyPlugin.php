<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Client\ClickAndCollectExample\Plugin;

use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculatorExtension\Dependency\Plugin\ProductOfferServicePointAvailabilityCalculatorStrategyPluginInterface;

/**
 * @method \Spryker\Client\ClickAndCollectExample\ClickAndCollectExampleFactory getFactory()
 */
class ExampleClickAndCollectProductOfferServicePointAvailabilityCalculatorStrategyPlugin extends AbstractPlugin implements ProductOfferServicePointAvailabilityCalculatorStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns `true` if service point UUIDs and request items are provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer $productOfferServicePointAvailabilityConditionsTransfer
     *
     * @return bool
     */
    public function isApplicable(
        ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer,
        ProductOfferServicePointAvailabilityConditionsTransfer $productOfferServicePointAvailabilityConditionsTransfer
    ): bool {
        return $productOfferServicePointAvailabilityConditionsTransfer->getServicePointUuids() &&
            $productOfferServicePointAvailabilityConditionsTransfer->getProductOfferServicePointAvailabilityRequestItems()->count();
    }

    /**
     * {@inheritDoc}
     * - Requires `ProductOfferServicePointAvailabilityRequestItemTransfer.productConcreteSku` to be set.
     * - Requires `ProductOfferServicePointAvailabilityRequestItemTransfer.quantity` to be set.
     * - Requires `ProductOfferServicePointAvailabilityRequestItemTransfer.identifier` to be set.
     * - Requires `ProductOfferServicePointAvailabilityResponseItemTransfer.availableQuantity` to be set.
     * - Requires `ProductOfferServicePointAvailabilityResponseItemTransfer.isNeverOutOfStock` to be set.
     * - Requires `ProductOfferServicePointAvailabilityResponseItemTransfer.servicePointUuid` to be set.
     * - Requires `ProductOfferServicePointAvailabilityResponseItemTransfer.productConcreteSku` to be set.
     * - Expects `ProductOfferServicePointAvailabilityRequestItemTransfer.productOfferReference` to be set.
     * - Calculates product offer availabilities per service point for each item in request.
     * - Searches among all available product offers for the product.
     * - In case `ProductOfferServicePointAvailabilityRequestItemTransfer.merchantReference` set, searches for available offers only from the same merchant.
     * - In case `ProductOfferServicePointAvailabilityRequestItemTransfer.productOfferReference` set, searches for availability of requested offer first, and for alternatives after.
     * - Otherwise, searches for non-merchant-related offers only.
     * - In case there is another product offer which matches criteria found, it will be used for calculation.
     * - Returns ProductOfferServicePointAvailabilityResponseItemTransfer objects mapped by service point UUID for requested items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer $productOfferServicePointAvailabilityConditionsTransfer
     *
     * @return array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>>
     */
    public function calculateProductOfferServicePointAvailabilities(
        ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer,
        ProductOfferServicePointAvailabilityConditionsTransfer $productOfferServicePointAvailabilityConditionsTransfer
    ): array {
        return $this->getFactory()
            ->createProductOfferServicePointAvailabilityCalculator()
            ->calculateProductOfferServicePointAvailabilities(
                $productOfferServicePointAvailabilityCollectionTransfer,
                $productOfferServicePointAvailabilityConditionsTransfer,
            );
    }
}
