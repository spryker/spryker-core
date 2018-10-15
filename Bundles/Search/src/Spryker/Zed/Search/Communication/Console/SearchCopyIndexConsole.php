<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Search\Business\SearchFacadeInterface getFacade()
 */
class SearchCopyIndexConsole extends Console
{
    public const COMMAND_NAME = 'search:index:copy';
    public const DESCRIPTION = 'This command will copy one index to another.';

    public const ARGUMENT_SOURCE = 'source';
    public const ARGUMENT_TARGET = 'target';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        $this->addArgument(static::ARGUMENT_SOURCE, InputArgument::REQUIRED, 'Name of the source index to copy.');
        $this->addArgument(static::ARGUMENT_TARGET, InputArgument::REQUIRED, 'Name of the target index to copy source index to.');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $source = $input->getArgument(static::ARGUMENT_SOURCE);
        $target = $input->getArgument(static::ARGUMENT_TARGET);

        if ($this->getFacade()->copyIndex($source, $target)) {
            return static::CODE_SUCCESS;
        }

        return static::CODE_ERROR;
    }
}
