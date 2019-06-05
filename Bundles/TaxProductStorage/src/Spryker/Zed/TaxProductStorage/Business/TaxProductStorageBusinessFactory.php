<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\TaxProductStorage\Business\TaxProductStoragePublisher\TaxProductStoragePublisher;
use Spryker\Zed\TaxProductStorage\Business\TaxProductStoragePublisher\TaxProductStoragePublisherInterface;
use Spryker\Zed\TaxProductStorage\Business\TaxProductStorageUnpublisher\TaxProductStorageUnpublisher;
use Spryker\Zed\TaxProductStorage\Business\TaxProductStorageUnpublisher\TaxProductStorageUnpublisherInterface;

/**
 * @method \Spryker\Zed\TaxProductStorage\TaxProductStorageConfig getConfig()
 * @method \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageEntityManagerInterface getEntityManager()
 */
class TaxProductStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\TaxProductStorage\Business\TaxProductStoragePublisher\TaxProductStoragePublisherInterface
     */
    public function createTaxProductStoragePublisher(): TaxProductStoragePublisherInterface
    {
        return new TaxProductStoragePublisher(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\TaxProductStorage\Business\TaxProductStorageUnpublisher\TaxProductStorageUnpublisherInterface
     */
    public function createTaxProductStorageUnpublisher(): TaxProductStorageUnpublisherInterface
    {
        return new TaxProductStorageUnpublisher(
            $this->getEntityManager()
        );
    }
}
