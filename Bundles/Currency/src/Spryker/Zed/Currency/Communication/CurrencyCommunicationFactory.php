<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Communication;

use Spryker\Zed\Currency\Communication\Mapper\StoreWithCurrenciesMapper;
use Spryker\Zed\Currency\Communication\Mapper\StoreWithCurrenciesMapperInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Currency\Business\CurrencyFacadeInterface getFacade()
 * @method \Spryker\Zed\Currency\CurrencyConfig getConfig()
 * @method \Spryker\Zed\Currency\Persistence\CurrencyQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface getRepository()
 */
class CurrencyCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Currency\Communication\Mapper\StoreWithCurrenciesMapperInterface
     */
    public function createStoreWithCurrenciesMapper(): StoreWithCurrenciesMapperInterface
    {
        return new StoreWithCurrenciesMapper();
    }
}
