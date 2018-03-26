<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Offer\Business\Model\OfferConverter;
use Spryker\Zed\Offer\Business\Model\OfferConverterInterface;
use Spryker\Zed\Offer\Business\Model\OfferReader;
use Spryker\Zed\Offer\Business\Model\OfferReaderInterface;
use Spryker\Zed\Offer\Dependency\Facade\OfferToSalesFacadeInterface;
use Spryker\Zed\Offer\OfferDependencyProvider;

/**
 * @method \Spryker\Zed\Offer\OfferConfig getConfig()
 */
class OfferBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Offer\Business\Model\OfferReaderInterface
     */
    public function createOfferReader(): OfferReaderInterface
    {
        return new OfferReader(
            $this->getSalesFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Offer\Business\Model\OfferConverterInterface
     */
    public function createOfferConverter(): OfferConverterInterface
    {
        return new OfferConverter(
            $this->getSalesFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Offer\Dependency\Facade\OfferToSalesFacadeInterface
     */
    public function getSalesFacade(): OfferToSalesFacadeInterface
    {
        return $this->getProvidedDependency(OfferDependencyProvider::FACADE_SALES);
    }
}
