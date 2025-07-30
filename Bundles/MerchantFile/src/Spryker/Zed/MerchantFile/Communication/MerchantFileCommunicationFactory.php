<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantFile\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\MerchantFile\MerchantFileConfig getConfig()
 * @method \Spryker\Zed\MerchantFile\Persistence\MerchantFileRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantFile\Business\MerchantFileFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantFile\Persistence\MerchantFileEntityManagerInterface getEntityManager()
 */
class MerchantFileCommunicationFactory extends AbstractCommunicationFactory
{
}
