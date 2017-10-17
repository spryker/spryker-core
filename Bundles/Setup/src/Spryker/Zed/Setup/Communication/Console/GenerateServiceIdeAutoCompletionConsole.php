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
use Spryker\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\GeneratedInterfaceMethodTagBuilder;
use Spryker\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\ServiceMethodTagBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @deprecated Will be removed with next major release
 */
class GenerateServiceIdeAutoCompletionConsole extends Console
{
    const COMMAND_NAME = 'dev:ide:generate-service-auto-completion';
    const APPLICATION_SERVICE = 'Service';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Generate IDE auto completion files for Service.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->generateServiceInterface();
        $this->generateServiceBundleInterface();
    }

    /**
     * @return void
     */
    protected function generateServiceInterface()
    {
        $options = $this->getServiceDefaultOptions();

        $generator = new IdeAutoCompletionGenerator($options, $this);
        $generator
            ->addMethodTagBuilder(new GeneratedInterfaceMethodTagBuilder([
                GeneratedInterfaceMethodTagBuilder::OPTION_METHOD_STRING_PATTERN => ' * @method \Generated\Service\Ide\{{bundle}} {{methodName}}()',
            ]));

        $generator->create();

        $this->info('Generated Service IdeAutoCompletion file');
    }

    /**
     * @return array
     */
    protected function getServiceDefaultOptions()
    {
        $bundleNameFinder = new BundleNameFinder([
            BundleNameFinder::OPTION_KEY_BUNDLE_PROJECT_PATH_PATTERN => Config::get(KernelConstants::PROJECT_NAMESPACE) . DIRECTORY_SEPARATOR,
            BundleNameFinder::OPTION_KEY_APPLICATION => self::APPLICATION_SERVICE,
        ]);

        $options = [
            IdeAutoCompletionGenerator::OPTION_KEY_NAMESPACE => 'Generated\Service\Ide',
            IdeAutoCompletionGenerator::OPTION_KEY_LOCATION_DIR => APPLICATION_SOURCE_DIR . '/Generated/Service/Ide/',
            IdeAutoCompletionGenerator::OPTION_KEY_BUNDLE_NAME_FINDER => $bundleNameFinder,
        ];

        return $options;
    }

    /**
     * @return void
     */
    protected function generateServiceBundleInterface()
    {
        $options = $this->getServiceDefaultOptions();
        $options[IdeBundleAutoCompletionGenerator::OPTION_KEY_INTERFACE_NAME] = 'BundleAutoCompletion';

        $generator = new IdeBundleAutoCompletionGenerator($options);
        $generator
            ->addMethodTagBuilder(new ServiceMethodTagBuilder());

        $generator->create();

        $this->info('Generated Service IdeBundleAutoCompletion file');
    }
}
