<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipment\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantShipment\Business\Reader\MerchantShipmentReader;
use Spryker\Zed\MerchantShipment\Business\Reader\MerchantShipmentReaderInterface;

/**
 * @method \Spryker\Zed\MerchantShipment\MerchantShipmentConfig getConfig()
 * @method \Spryker\Zed\MerchantShipment\Persistence\MerchantShipmentRepositoryInterface getRepository()
 */
class MerchantShipmentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantShipment\Business\Reader\MerchantShipmentReaderInterface
     */
    public function createMerchantShipmentReader(): MerchantShipmentReaderInterface
    {
        return new MerchantShipmentReader($this->getRepository());
    }
}
