<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Business\Model;

use Generated\Shared\Transfer\CurrencyTransfer;
use Orm\Zed\Currency\Persistence\SpyCurrency;

class CurrencyWriter implements CurrencyWriterInterface
{

    /**
     * @var \Spryker\Zed\Currency\Business\Model\CurrencyMapperInterface
     */
    protected $currencyMapper;

    /**
     * @param \Spryker\Zed\Currency\Business\Model\CurrencyMapperInterface $currencyMapper
     */
    public function __construct(CurrencyMapperInterface $currencyMapper)
    {
        $this->currencyMapper = $currencyMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return int
     */
    public function create(CurrencyTransfer $currencyTransfer)
    {
        $currencyEntity = $this->createCurrencyEntity();
        $currencyEntity = $this->currencyMapper->mapTransferToEntity($currencyEntity, $currencyTransfer);

        $currencyEntity->save();

        return $currencyEntity->getPrimaryKey();
    }

    /**
     * @return \Orm\Zed\Currency\Persistence\SpyCurrency
     */
    protected function createCurrencyEntity()
    {
        return new SpyCurrency();
    }

}
