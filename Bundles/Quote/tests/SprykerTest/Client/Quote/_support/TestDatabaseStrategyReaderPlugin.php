<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\QuoteExtension\Dependency\Plugin\DatabaseStrategyReaderPluginInterface;

class TestDatabaseStrategyReaderPlugin implements DatabaseStrategyReaderPluginInterface
{
    /**
     * @var string
     */
    public const CUSTOMER_REFERENCE = 'test-customer-reference';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $quoteTransfer->setCustomerReference(static::CUSTOMER_REFERENCE);
    }
}
