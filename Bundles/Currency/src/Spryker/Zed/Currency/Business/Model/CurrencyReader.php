<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Business\Model;

use Spryker\Zed\Currency\Business\Model\Exception\CurrencyNotFoundException;
use Spryker\Zed\Currency\Persistence\CurrencyQueryContainerInterface;

class CurrencyReader implements CurrencyReaderInterface
{

    /**
     * @var \Spryker\Zed\Currency\Persistence\CurrencyQueryContainerInterface
     */
    protected $currencyQueryContainer;

    /**
     * @var \Spryker\Zed\Currency\Business\Model\CurrencyMapperInterface
     */
    protected $currencyMapper;

    /**
     * @param \Spryker\Zed\Currency\Persistence\CurrencyQueryContainerInterface $currencyQueryContainer
     * @param \Spryker\Zed\Currency\Business\Model\CurrencyMapperInterface $currencyMapper
     */
    public function __construct(
        CurrencyQueryContainerInterface $currencyQueryContainer,
        CurrencyMapperInterface $currencyMapper
    ) {

        $this->currencyQueryContainer = $currencyQueryContainer;
        $this->currencyMapper = $currencyMapper;
    }

    /**
     * @param int $idCurrency
     *
     * @throws \Spryker\Zed\Currency\Business\Model\Exception\CurrencyNotFoundException
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getByIdCurrency($idCurrency)
    {
        $currencyEntity = $this->currencyQueryContainer
            ->queryCurrencyByIdCurrency($idCurrency)
            ->findOne();

        if (!$currencyEntity) {
            throw new CurrencyNotFoundException(
                sprintf('Currency with id "%s" not found.', $idCurrency)
            );
        }

        return $this->currencyMapper->mapEntityToTransfer($currencyEntity);
    }

}
