<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesOrderAmendment;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\SalesOrderAmendment\SalesOrderAmendmentFactory getFactory()
 */
class SalesOrderAmendmentClient extends AbstractClient implements SalesOrderAmendmentClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function cancelOrderAmendment(): void
    {
        $this->getFactory()->getQuoteClient()->clearQuote();
    }
}
