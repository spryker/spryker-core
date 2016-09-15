<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Currency\Builder;

use Generated\Shared\Transfer\CurrencyTransfer;
use Symfony\Component\Intl\ResourceBundle\CurrencyBundleInterface;

class CurrencyBuilder implements CurrencyBuilderInterface
{

    /**
     * @var \Symfony\Component\Intl\ResourceBundle\CurrencyBundleInterface
     */
    protected $currencyBundle;

    /**
     * @var string
     */
    protected $defaultIsoCode;

    /**
     * @param \Symfony\Component\Intl\ResourceBundle\CurrencyBundleInterface $currencyBundle
     * @param string $defaultIsoCode
     */
    public function __construct(CurrencyBundleInterface $currencyBundle, $defaultIsoCode)
    {
        $this->currencyBundle = $currencyBundle;
        $this->defaultIsoCode = $defaultIsoCode;
    }

    /**
     * @param string $isoCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function fromIsoCode($isoCode)
    {
        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode($isoCode);
        $currencyTransfer->setName($this->currencyBundle->getCurrencyName($isoCode));
        $currencyTransfer->setSymbol($this->currencyBundle->getCurrencySymbol($isoCode));
        $currencyTransfer->setIsDefault($isoCode === $this->defaultIsoCode);

        return $currencyTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrent()
    {
        return $this->fromIsoCode($this->defaultIsoCode);
    }

}
