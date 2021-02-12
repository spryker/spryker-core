<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GracefulRunner\Business;

use Generator;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\GracefulRunner\Business\GracefulRunnerBusinessFactory getFactory()
 * @method \Spryker\Zed\GracefulRunner\Persistence\GracefulRunnerRepositoryInterface getRepository()
 * @method \Spryker\Zed\GracefulRunner\Persistence\GracefulRunnerEntityManagerInterface getEntityManager()
 */
class GracefulRunnerFacade extends AbstractFacade implements GracefulRunnerFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generator $generator
     * @param string|null $throwableClassName
     *
     * @return int
     */
    public function run(Generator $generator, ?string $throwableClassName = null): int
    {
        return $this->getFactory()->createGracefulRunner()->run($generator, $throwableClassName);
    }
}
