<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuoteRequestConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesQuoteRequestConnector\Business\Writer\SalesQuoteRequestWriter;
use Spryker\Zed\SalesQuoteRequestConnector\Business\Writer\SalesQuoteRequestWriterInterface;

/**
 * @method \Spryker\Zed\SalesQuoteRequestConnector\Persistence\SalesQuoteRequestConnectorEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesQuoteRequestConnector\SalesQuoteRequestConnectorConfig getConfig()
 */
class SalesQuoteRequestConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesQuoteRequestConnector\Business\Writer\SalesQuoteRequestWriterInterface
     */
    public function createSalesQuoteRequestWriter(): SalesQuoteRequestWriterInterface
    {
        return new SalesQuoteRequestWriter($this->getEntityManager());
    }
}
