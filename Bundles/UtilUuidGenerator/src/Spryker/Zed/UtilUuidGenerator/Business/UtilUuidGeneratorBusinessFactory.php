<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilUuidGenerator\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\UtilUuidGenerator\Business\Generator\UuidGenerator;
use Spryker\Zed\UtilUuidGenerator\Business\Generator\UuidGeneratorInterface;

/**
 * @method \Spryker\Zed\UtilUuidGenerator\UtilUuidGeneratorConfig getConfig()
 * @method \Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorRepositoryInterface getRepository()()
 */
class UtilUuidGeneratorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\UtilUuidGenerator\Business\Generator\UuidGeneratorInterface
     */
    public function createUuidGenerator(): UuidGeneratorInterface
    {
        return new UuidGenerator(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }
}
