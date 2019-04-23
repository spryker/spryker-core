<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Quote\Business\QuoteFacadeInterface;
use Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface;
use Spryker\Zed\SharedCartsRestApi\Business\SharedCart\SharedCartReader;
use Spryker\Zed\SharedCartsRestApi\Business\SharedCart\SharedCartReaderInterface;

/**
 * @method \Pyz\Zed\SharedCartsRestApi\SharedCartsRestApiConfig getConfig()
 * @method \Pyz\Zed\SharedCartsRestApi\Persistence\SharedCartsRestApiQueryContainer getQueryContainer()
 */
class SharedCartsRestApiBusinessFactory extends AbstractBusinessFactory
{

    public function createSharedCartReader(): SharedCartReaderInterface
    {
        return new SharedCartReader(
            $this->getQuoteFacade(),
            $this->getSharedCartFacade()
        );
    }

    public function getQuoteFacade(): QuoteFacadeInterface
    {

    }

    public function getSharedCartFacade(): SharedCartFacadeInterface
    {

    }
}
