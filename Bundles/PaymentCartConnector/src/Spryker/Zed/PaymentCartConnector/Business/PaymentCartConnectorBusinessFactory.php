<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentCartConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PaymentCartConnector\Business\Remover\QuotePaymentRemover;
use Spryker\Zed\PaymentCartConnector\Business\Remover\QuotePaymentRemoverInterface;

/**
 * @method \Spryker\Zed\PaymentCartConnector\PaymentCartConnectorConfig getConfig()
 */
class PaymentCartConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PaymentCartConnector\Business\Remover\QuotePaymentRemoverInterface
     */
    public function createQuotePaymentRemover(): QuotePaymentRemoverInterface
    {
        return new QuotePaymentRemover($this->getConfig());
    }
}
