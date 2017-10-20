<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Setup\Communication\Console;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Zed\Kernel\BundleNameFinder;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Spryker\Zed\Kernel\IdeAutoCompletion\IdeAutoCompletionGenerator;
use Spryker\Zed\Kernel\IdeAutoCompletion\IdeBundleAutoCompletionGenerator;
use Spryker\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\ClientMethodTagBuilder;
use Spryker\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\FacadeMethodTagBuilder;
use Spryker\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\GeneratedInterfaceMethodTagBuilder;
use Spryker\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\QueryContainerMethodTagBuilder;
use Spryker\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\ServiceMethodTagBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @deprecated Will be removed with next major release
 */
class GenerateZedIdeAutoCompletionConsole extends Console
{
    const COMMAND_NAME = 'dev:ide:generate-zed-auto-completion';
    const APPLICATION_ZED = 'Zed';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Generate IDE auto completion files for Zed.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->generateZedInterface();
        $this->generateZedBundleInterface();
    }

    /**
     * @return void
     */
    protected function generateZedInterface()
    {
        $options = $this->getZedDefaultOptions();

        $generator = new IdeAutoCompletionGenerator($options, $this);
        $generator
            ->addMethodTagBuilder(new GeneratedInterfaceMethodTagBuilder());

        $generator->create();

        $this->info('Generated Zed IdeAutoCompletion file');
    }

    /**
     * @return array
     */
    protected function getZedDefaultOptions()
    {
        $bundleNameFinder = new BundleNameFinder([
            BundleNameFinder::OPTION_KEY_BUNDLE_PROJECT_PATH_PATTERN => Config::get(KernelConstants::PROJECT_NAMESPACE) . DIRECTORY_SEPARATOR,
            BundleNameFinder::OPTION_KEY_APPLICATION => '*',
        ]);

        $options = [
            IdeAutoCompletionGenerator::OPTION_KEY_NAMESPACE => 'Generated\Zed\Ide',
            IdeAutoCompletionGenerator::OPTION_KEY_LOCATION_DIR => APPLICATION_SOURCE_DIR . '/Generated/Zed/Ide/',
            IdeAutoCompletionGenerator::OPTION_KEY_BUNDLE_NAME_FINDER => $bundleNameFinder,
        ];

        return $options;
    }

    /**
     * @return void
     */
    protected function generateZedBundleInterface()
    {
        $options = $this->getZedDefaultOptions();
        $options[IdeBundleAutoCompletionGenerator::OPTION_KEY_INTERFACE_NAME] = 'BundleAutoCompletion';
        $generator = new IdeBundleAutoCompletionGenerator($options);
        $generator
            ->addMethodTagBuilder(new FacadeMethodTagBuilder())
            ->addMethodTagBuilder(new QueryContainerMethodTagBuilder())
            ->addMethodTagBuilder(new ClientMethodTagBuilder())
            ->addMethodTagBuilder(new ServiceMethodTagBuilder());

        $generator->create();

        $this->info('Generated Zed IdeBundleAutoCompletion file');
    }
}
