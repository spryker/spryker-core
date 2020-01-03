<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Business\Model;

use Generated\Shared\Transfer\CurrencyTransfer;
use Orm\Zed\Currency\Persistence\SpyCurrency;
use Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationInterface;

class CurrencyMapper implements CurrencyMapperInterface
{
    /**
     * @var \Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationInterface
     */
    protected $currencyRepository;

    /**
     * @param \Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationInterface $currencyRepository
     */
    public function __construct(CurrencyToInternationalizationInterface $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * @param \Orm\Zed\Currency\Persistence\SpyCurrency $currencyEntity
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function mapEntityToTransfer(SpyCurrency $currencyEntity)
    {
        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setFractionDigits(
            $this->currencyRepository->getFractionDigits($currencyEntity->getCode())
        );

        return $currencyTransfer->fromArray($currencyEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\Currency\Persistence\SpyCurrency $currencyEntity
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Orm\Zed\Currency\Persistence\SpyCurrency
     */
    public function mapTransferToEntity(SpyCurrency $currencyEntity, CurrencyTransfer $currencyTransfer)
    {
        $currencyEntity->fromArray($currencyTransfer->toArray());

        return $currencyEntity;
    }
}
