<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductResourceAliasStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PriceProductResourceAliasStorage\Business\PriceProductStorage\PriceProductAbstractStorageWriter;
use Spryker\Zed\PriceProductResourceAliasStorage\Business\PriceProductStorage\PriceProductAbstractStorageWriterInterface;
use Spryker\Zed\PriceProductResourceAliasStorage\Business\PriceProductStorage\PriceProductConcreteStorageWriter;
use Spryker\Zed\PriceProductResourceAliasStorage\Business\PriceProductStorage\PriceProductConcreteStorageWriterInterface;

/**
 * @method \Spryker\Zed\PriceProductResourceAliasStorage\Persistence\PriceProductResourceAliasStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PriceProductResourceAliasStorage\Persistence\PriceProductResourceAliasStorageRepositoryInterface getRepository()
 */
class PriceProductResourceAliasStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PriceProductResourceAliasStorage\Business\PriceProductStorage\PriceProductAbstractStorageWriterInterface
     */
    public function createPriceProductAbstractStorageWriter(): PriceProductAbstractStorageWriterInterface
    {
        return new PriceProductAbstractStorageWriter(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductResourceAliasStorage\Business\PriceProductStorage\PriceProductConcreteStorageWriterInterface
     */
    public function createPriceProductConcreteStorageWriter(): PriceProductConcreteStorageWriterInterface
    {
        return new PriceProductConcreteStorageWriter(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }
}
