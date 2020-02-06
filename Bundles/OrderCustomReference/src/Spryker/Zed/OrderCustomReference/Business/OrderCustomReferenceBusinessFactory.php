<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\OrderCustomReference\Business\Provider\QuoteFieldsProvider;
use Spryker\Zed\OrderCustomReference\Business\Provider\QuoteFieldsProviderInterface;

/**
 * @method \Spryker\Zed\OrderCustomReference\OrderCustomReferenceConfig getConfig()
 */
class OrderCustomReferenceBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\OrderCustomReference\Business\Provider\QuoteFieldsProviderInterface
     */
    public function createQuoteFieldsProvider(): QuoteFieldsProviderInterface
    {
        return new QuoteFieldsProvider();
    }
}
