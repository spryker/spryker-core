<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOffer\Business\Reader\ProductOfferReader;
use Spryker\Zed\ProductOffer\Business\Reader\ProductOfferReaderInterface;
use Spryker\Zed\ProductOffer\Business\Writer\ProductOfferWriter;
use Spryker\Zed\ProductOffer\Business\Writer\ProductOfferWriterInterface;

/**
 * @method \Spryker\Zed\ProductOffer\ProductOfferConfig getConfig()
 * @method \Spryker\Zed\ProductOffer\Persistence\ProductOfferEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface getRepository()
 */
class ProductOfferBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOffer\Business\Reader\ProductOfferReaderInterface
     */
    public function createProductOfferReader(): ProductOfferReaderInterface
    {
        return new ProductOfferReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Business\Writer\ProductOfferWriterInterface
     */
    public function createProductOfferWriter(): ProductOfferWriterInterface
    {
        return new ProductOfferWriter($this->getEntityManager());
    }
}
