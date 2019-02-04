<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Uuid\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Uuid\UuidConfig getConfig()
 * @method \Spryker\Zed\Uuid\Business\UuidFacadeInterface getFacade()
 * @method \Spryker\Zed\Uuid\Persistence\UuidEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Uuid\Persistence\UuidRepositoryInterface getRepository()
 */
class UuidCommunicationFactory extends AbstractCommunicationFactory
{
}
