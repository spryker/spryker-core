<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Merchant\Business\Model\MerchantReader;
use Spryker\Zed\Merchant\Business\Model\MerchantReaderInterface;
use Spryker\Zed\Merchant\Business\Model\MerchantWriter;
use Spryker\Zed\Merchant\Business\Model\MerchantWriterInterface;

/**
 * @method \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface getRepository()
 * @method \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Merchant\MerchantConfig getConfig()
 */
class MerchantBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Merchant\Business\Model\MerchantWriterInterface
     */
    public function createMerchantWriter(): MerchantWriterInterface
    {
        return new MerchantWriter(
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\Merchant\Business\Model\MerchantReaderInterface
     */
    public function createMerchantReader(): MerchantReaderInterface
    {
        return new MerchantReader(
            $this->getRepository()
        );
    }
}
