<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestRequestValidator\Communication\Stub;

class RestRequestValidatorToCurrencyQueryContainerBridge implements RestRequestValidatorToCurrencyQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Currency\Persistence\CurrencyQueryContainerInterface
     */
    protected $currencyQueryContainer;

    /**
     * @param \Spryker\Zed\Currency\Persistence\CurrencyQueryContainerInterface $currencyQueryContainer
     */
    public function __construct($currencyQueryContainer)
    {
        $this->currencyQueryContainer = $currencyQueryContainer;
    }

    /**
     * @param string $isoCode
     *
     * @return \Orm\Zed\Currency\Persistence\SpyCurrencyQuery
     */
    public function queryCurrencyByIsoCode(string $isoCode)
    {
        return $this->currencyQueryContainer->queryCurrencyByIsoCode($isoCode);
    }
}
