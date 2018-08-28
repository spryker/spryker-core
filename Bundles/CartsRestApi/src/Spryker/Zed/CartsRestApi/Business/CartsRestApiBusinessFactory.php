<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business;

use Spryker\Zed\CartsRestApi\Business\Quote\QuoteUuidWriter;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteUuidWriterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CartsRestApi\Persistence\CartsRestApiEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CartsRestApi\CartsRestApiConfig getConfig()
 */
class CartsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CartsRestApi\Business\Quote\QuoteUuidWriterInterface
     */
    public function createQuoteUuidWriter(): QuoteUuidWriterInterface
    {
        return new QuoteUuidWriter(
            $this->getEntityManager()
        );
    }
}
