<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\PriceProductStorage\PriceProductStorageConfig getConfig()
 * @method \Spryker\Zed\PriceProductStorage\Business\PriceProductStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferRepositoryInterface getRepository()
 * @method \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferEntityManagerInterface getEntityManager()
 */
class PriceProductOfferCommunicationFactory extends AbstractCommunicationFactory
{
}
