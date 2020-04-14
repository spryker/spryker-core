<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Stock\StockConfig getConfig()
 * @method \Spryker\Zed\Stock\Business\StockFacadeInterface getFacade()
 * @method \Spryker\Zed\Stock\Persistence\StockRepositoryInterface getRepository()
 * @method \Spryker\Zed\Stock\Persistence\StockEntityManagerInterface getEntityManager()
 */
class StockCommunicationFactory extends AbstractCommunicationFactory
{
}
