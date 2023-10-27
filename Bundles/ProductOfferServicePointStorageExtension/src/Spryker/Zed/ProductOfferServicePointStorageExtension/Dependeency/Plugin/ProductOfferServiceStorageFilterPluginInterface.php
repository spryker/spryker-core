<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorageExtension\Dependeency\Plugin;

use Spryker\Zed\ProductOfferServicePointStorageExtension\Dependency\Plugin\ProductOfferServiceCollectionStorageFilterPluginInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\ProductOfferServicePointStorageExtension\Dependency\Plugin\ProductOfferServiceCollectionStorageFilterPluginInterface} instead.
 *
 * Provides ability to filter product offer services collection by provided criteria.
 * This plugin stack gets executed after a list of `ProductOfferServicesTransfer` for publishing is retrieved from Persistence.
 */
interface ProductOfferServiceStorageFilterPluginInterface extends ProductOfferServiceCollectionStorageFilterPluginInterface
{
}
