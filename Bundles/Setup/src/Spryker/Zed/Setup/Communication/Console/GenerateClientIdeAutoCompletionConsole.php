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
use Spryker\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\GeneratedInterfaceMethodTagBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @deprecated Will be removed with next major release
 */
class GenerateClientIdeAutoCompletionConsole extends Console
{
    const COMMAND_NAME = 'dev:ide:generate-client-auto-completion';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Generate IDE auto completion files for Client.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->generateClientInterface();
        $this->generateClientBundleInterface();
    }

    /**
     * @return void
     */
    protected function generateClientInterface()
    {
        $options = $this->getClientDefaultOptions();

        $generator = new IdeAutoCompletionGenerator($options, $this);
        $generator
            ->addMethodTagBuilder(new GeneratedInterfaceMethodTagBuilder(
                [
                    GeneratedInterfaceMethodTagBuilder::OPTION_METHOD_STRING_PATTERN => ' * @method \\Generated\Client\Ide\{{bundle}} {{methodName}}()',
                ]
            ));
        $generator->create();

        $this->info('Generated Client IdeAutoCompletion file');
    }

    /**
     * @return array
     */
    protected function getClientDefaultOptions()
    {
        $options = [
            IdeAutoCompletionGenerator::OPTION_KEY_NAMESPACE => 'Generated\Client\Ide',
            IdeAutoCompletionGenerator::OPTION_KEY_LOCATION_DIR => APPLICATION_SOURCE_DIR . '/Generated/Client/Ide/',
            IdeAutoCompletionGenerator::OPTION_KEY_APPLICATION => 'Client',
            IdeAutoCompletionGenerator::OPTION_KEY_BUNDLE_NAME_FINDER => new BundleNameFinder(
                [
                    BundleNameFinder::OPTION_KEY_APPLICATION => 'Client',
                    BundleNameFinder::OPTION_KEY_BUNDLE_PROJECT_PATH_PATTERN => $this->getProjectNamespace() . '/',
                ]
            ),
        ];

        return $options;
    }

    /**
     * @return void
     */
    protected function generateClientBundleInterface()
    {
        $options = $this->getClientDefaultOptions();
        $options[IdeBundleAutoCompletionGenerator::OPTION_KEY_INTERFACE_NAME] = 'BundleAutoCompletion';

        $generator = new IdeBundleAutoCompletionGenerator($options);
        $generator
            ->addMethodTagBuilder(new ClientMethodTagBuilder());

        $generator->create();

        $this->info('Generated Client IdeBundleAutoCompletion file');
    }

    /**
     * @return string
     */
    private function getProjectNamespace()
    {
        return Config::get(KernelConstants::PROJECT_NAMESPACES)[0];
    }
}
