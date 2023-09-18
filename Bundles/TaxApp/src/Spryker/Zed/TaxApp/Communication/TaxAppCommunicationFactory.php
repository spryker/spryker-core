<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\TaxApp\TaxAppConfig getConfig()
 * @method \Spryker\Zed\TaxApp\Business\TaxAppFacadeInterface getFacade()
 * @method \Spryker\Zed\TaxApp\Persistence\TaxAppRepositoryInterface getRepository()
 * @method \Spryker\Zed\TaxApp\Persistence\TaxAppEntityManagerInterface getEntityManager()
 */
class TaxAppCommunicationFactory extends AbstractCommunicationFactory
{
}
