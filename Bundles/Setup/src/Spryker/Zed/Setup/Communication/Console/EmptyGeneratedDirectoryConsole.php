<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Setup\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @deprecated Use commands for several other modules instead.
 * @see \Spryker\Zed\Development\Communication\Console\RemoveIdeAutoCompletionConsole
 * @see \Spryker\Zed\Development\Communication\Console\RemoveClientIdeAutoCompletionConsole
 * @see \Spryker\Zed\Development\Communication\Console\RemoveGlueIdeAutoCompletionConsole
 * @see \Spryker\Zed\Development\Communication\Console\RemoveServiceIdeAutoCompletionConsole
 * @see \Spryker\Zed\Development\Communication\Console\RemoveYvesIdeAutoCompletionConsole
 * @see \Spryker\Zed\Development\Communication\Console\RemoveZedIdeAutoCompletionConsole
 * @see \Spryker\Zed\Transfer\Communication\Console\RemoveTransferConsole
 * @see \Spryker\Zed\Propel\Communication\Console\RemoveEntityTransferConsole
 * @see \Spryker\Zed\Transfer\Communication\Console\RemoveDataBuilderConsole
 * @see \Spryker\Zed\RestRequestValidator\Communication\Console\RemoveValidationCacheConsole
 * @see \Spryker\Zed\Search\Communication\Console\RemoveSourceMapConsole
 * @see \Spryker\Zed\ZedNavigation\Communication\Console\RemoveNavigationCacheConsole
 * @method \Spryker\Zed\Setup\Business\SetupFacadeInterface getFacade()
 * @method \Spryker\Zed\Setup\Communication\SetupCommunicationFactory getFactory()
 */
class EmptyGeneratedDirectoryConsole extends Console
{
    public const COMMAND_NAME = 'setup:empty-generated-directory';

    /**
     * @return void
     */
    public function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Empty the directory where generated files are stored');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->warning(
            sprintf(
                'The console command `%s` is deprecated. Use the following commands instead : %s',
                static::COMMAND_NAME,
                implode(
                    ', ',
                    [
                        'dev:ide:remove-auto-completion',
                        'dev:ide:remove-client-auto-completion',
                        'dev:ide:remove-glue-auto-completion',
                        'dev:ide:remove-service-auto-completion',
                        'dev:ide:remove-yves-auto-completion',
                        'dev:ide:remove-zed-auto-completion',
                        'glue:rest:remove-request-validation-cache',
                        'navigation:remove-cache',
                        'search:setup:remove-source-map',
                        'transfer:databuilder:remove',
                        'transfer:entity:remove',
                        'transfer:remove',
                    ]
                )
            )
        );

        $this->getFacade()->emptyGeneratedDirectory();

        return null;
    }
}
