<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductOfferServicePoint\ProductOfferServicePointConfig getConfig()
 * @method \Spryker\Zed\ProductOfferServicePoint\Business\ProductOfferServicePointFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointEntityManagerInterface getEntityManager()
 */
class ProductOfferServicePointCommunicationFactory extends AbstractCommunicationFactory
{
}
