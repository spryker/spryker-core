<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantOms\Dependency\Service\MerchantOmsToUtilDataReaderServiceInterface;
use Spryker\Zed\MerchantOms\MerchantOmsDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantOms\MerchantOmsConfig getConfig()
 * @method \Spryker\Zed\MerchantOms\Business\MerchantOmsFacadeInterface getFacade()()
 */
class MerchantOmsCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantOms\Dependency\Service\MerchantOmsToUtilDataReaderServiceInterface
     */
    public function getUtilDataReaderService(): MerchantOmsToUtilDataReaderServiceInterface
    {
        return $this->getProvidedDependency(MerchantOmsDependencyProvider::SERVICE_UTIL_DATA_READER);
    }
}
