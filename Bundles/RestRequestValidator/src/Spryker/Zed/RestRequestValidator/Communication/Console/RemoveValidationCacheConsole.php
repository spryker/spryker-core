<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\RestRequestValidator\Communication\Console\RemoveRestApiValidationCacheConsole} instead.
 *
 * @method \Spryker\Zed\RestRequestValidator\Business\RestRequestValidatorFacadeInterface getFacade()
 */
class RemoveValidationCacheConsole extends Console
{
    protected const COMMAND_NAME = 'glue:rest-request-validation-cache:remove';
    protected const DESCRIPTION = 'Removes the cache for rest request validation rules.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getMessenger()->info(static::DESCRIPTION);
        $this->getFacade()->removeValidationCache();

        return static::CODE_SUCCESS;
    }
}
