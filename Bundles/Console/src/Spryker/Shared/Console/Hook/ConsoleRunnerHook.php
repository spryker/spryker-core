<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Console\Hook;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleRunnerHook implements ConsoleRunnerHookInterface
{
    /**
     * @var \Spryker\Shared\Console\Dependency\Plugin\ConsolePreRunHookPluginInterface[]
     */
    protected $preHookPlugins;

    /**
     * @var \Spryker\Shared\Console\Dependency\Plugin\ConsolePostRunHookPluginInterface[]
     */
    protected $postHookPlugins;

    /**
     * @param \Spryker\Shared\Console\Dependency\Plugin\ConsolePreRunHookPluginInterface[] $preHookPlugins
     * @param \Spryker\Shared\Console\Dependency\Plugin\ConsolePostRunHookPluginInterface[] $postHookPlugins
     */
    public function __construct(array $preHookPlugins, array $postHookPlugins)
    {
        $this->preHookPlugins = $preHookPlugins;
        $this->postHookPlugins = $postHookPlugins;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function preRun(InputInterface $input, OutputInterface $output): void
    {
        foreach ($this->preHookPlugins as $preHookPlugin) {
            $preHookPlugin->preRun($input, $output);
        }
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function postRun(InputInterface $input, OutputInterface $output): void
    {
        foreach ($this->postHookPlugins as $postHookPlugin) {
            $postHookPlugin->postRun($input, $output);
        }
    }
}
