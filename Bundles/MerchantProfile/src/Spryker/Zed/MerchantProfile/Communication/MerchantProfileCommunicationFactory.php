<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantProfile\Communication\Expander\MerchantProfileExpander;
use Spryker\Zed\MerchantProfile\Communication\Expander\MerchantProfileExpanderInterface;

/**
 * @method \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantProfile\MerchantProfileConfig getConfig()
 * @method \Spryker\Zed\MerchantProfile\Business\MerchantProfileFacadeInterface getFacade()
 */
class MerchantProfileCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantProfile\Communication\Expander\MerchantProfileExpanderInterface
     */
    public function createMerchantProfileExpander(): MerchantProfileExpanderInterface
    {
        return new MerchantProfileExpander(
            $this->getRepository()
        );
    }
}
