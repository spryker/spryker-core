<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\TaxStorage\Business\TaxStoragePublisher\TaxStoragePublisher;
use Spryker\Zed\TaxStorage\Business\TaxStoragePublisher\TaxStoragePublisherInterface;
use Spryker\Zed\TaxStorage\Business\TaxStorageUnpublisher\TaxStorageUnpublisher;
use Spryker\Zed\TaxStorage\Business\TaxStorageUnpublisher\TaxStorageUnpublisherInterface;

/**
 * @method \Spryker\Zed\TaxStorage\Persistence\TaxStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\TaxStorage\Persistence\TaxStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\TaxStorage\TaxStorageConfig getConfig()
 */
class TaxStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\TaxStorage\Business\TaxStoragePublisher\TaxStoragePublisherInterface
     */
    public function createTaxStoragePublisher(): TaxStoragePublisherInterface
    {
        return new TaxStoragePublisher(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\TaxStorage\Business\TaxStorageUnpublisher\TaxStorageUnpublisherInterface
     */
    public function createTaxStorageUnpublisher(): TaxStorageUnpublisherInterface
    {
        return new TaxStorageUnpublisher(
            $this->getEntityManager()
        );
    }
}
