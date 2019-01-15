<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Price\Business\Validator\QuoteValidator;

/**
 * @method \Spryker\Zed\Price\PriceConfig getConfig()
 * @method \Spryker\Zed\Price\Business\PriceFacadeInterface getFacade()
 */
class PriceCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Price\Business\Validator\QuoteValidator
     */
    public function createQuoteValidator(): QuoteValidator
    {
        return new QuoteValidator($this->getFacade());
    }
}
