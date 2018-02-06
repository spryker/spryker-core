<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Currency\Builder;

use Generated\Shared\Transfer\CurrencyTransfer;
use Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationInterface;

class CurrencyBuilder implements CurrencyBuilderInterface
{
    /**
     * @var \Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationInterface
     */
    protected $currencyRepository;

    /**
     * @var string
     */
    protected $defaultIsoCode;

    /**
     * @var string
     */
    protected $currentCurrencyIsoCode;

    /**
     * @param \Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationInterface $currencyRepository
     * @param string $defaultIsoCode
     * @param string $currentCurrencyIsoCode
     */
    public function __construct(
        CurrencyToInternationalizationInterface $currencyRepository,
        $defaultIsoCode,
        $currentCurrencyIsoCode
    ) {
        $this->currencyRepository = $currencyRepository;
        $this->defaultIsoCode = $defaultIsoCode;
        $this->currentCurrencyIsoCode = $currentCurrencyIsoCode;
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
        $currencyTransfer->setName($this->currencyRepository->getNameByIsoCode($isoCode));
        $currencyTransfer->setSymbol($this->currencyRepository->getSymbolByIsoCode($isoCode));
        $currencyTransfer->setIsDefault($isoCode === $this->defaultIsoCode);
        $currencyTransfer->setFractionDigits($this->currencyRepository->getFractionDigits($isoCode));

        return $currencyTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrent()
    {
        return $this->fromIsoCode($this->currentCurrencyIsoCode);
    }
}
