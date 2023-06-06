<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferShipmentType\Business\Creator\ProductOfferShipmentTypeCreator;
use Spryker\Zed\ProductOfferShipmentType\Business\Creator\ProductOfferShipmentTypeCreatorInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Expander\ProductOfferExpander;
use Spryker\Zed\ProductOfferShipmentType\Business\Expander\ProductOfferExpanderInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Updater\ProductOfferShipmentTypeUpdater;
use Spryker\Zed\ProductOfferShipmentType\Business\Updater\ProductOfferShipmentTypeUpdaterInterface;
use Spryker\Zed\ProductOfferShipmentType\Dependency\Facade\ProductOfferShipmentTypeToShipmentTypeFacadeInterface;
use Spryker\Zed\ProductOfferShipmentType\ProductOfferShipmentTypeDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferShipmentType\ProductOfferShipmentTypeConfig getConfig()
 * @method \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeRepositoryInterface getRepository()
 */
class ProductOfferShipmentTypeBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferShipmentType\Business\Expander\ProductOfferExpanderInterface
     */
    public function createProductOfferExpander(): ProductOfferExpanderInterface
    {
        return new ProductOfferExpander(
            $this->getRepository(),
            $this->getShipmentTypeFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentType\Business\Creator\ProductOfferShipmentTypeCreatorInterface
     */
    public function createProductOfferShipmentTypeCreator(): ProductOfferShipmentTypeCreatorInterface
    {
        return new ProductOfferShipmentTypeCreator(
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentType\Business\Updater\ProductOfferShipmentTypeUpdaterInterface
     */
    public function createProductOfferShipmentTypeUpdater(): ProductOfferShipmentTypeUpdaterInterface
    {
        return new ProductOfferShipmentTypeUpdater(
            $this->getEntityManager(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentType\Dependency\Facade\ProductOfferShipmentTypeToShipmentTypeFacadeInterface
     */
    public function getShipmentTypeFacade(): ProductOfferShipmentTypeToShipmentTypeFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferShipmentTypeDependencyProvider::FACADE_SHIPMENT_TYPE);
    }
}
