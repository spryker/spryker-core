<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchHttp\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\SearchHttp\Persistence\SearchHttpRepositoryInterface getRepository()
 * @method \Spryker\Zed\SearchHttp\Business\SearchHttpFacadeInterface getFacade()
 * @method \Spryker\Zed\SearchHttp\SearchHttpConfig getConfig()
 * @method \Spryker\Zed\SearchHttp\Persistence\SearchHttpEntityManagerInterface getEntityManager()
 */
class SearchHttpCommunicationFactory extends AbstractCommunicationFactory
{
}
