<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Uuid\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Uuid\Business\Generator\UuidGenerator;
use Spryker\Zed\Uuid\Business\Generator\UuidGeneratorInterface;

/**
 * @method \Spryker\Zed\Uuid\UuidConfig getConfig()
 * @method \Spryker\Zed\Uuid\Persistence\UuidEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Uuid\Persistence\UuidRepositoryInterface getRepository()()
 */
class UuidBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Uuid\Business\Generator\UuidGeneratorInterface
     */
    public function createUuidGenerator(): UuidGeneratorInterface
    {
        return new UuidGenerator(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getConfig()
        );
    }
}
