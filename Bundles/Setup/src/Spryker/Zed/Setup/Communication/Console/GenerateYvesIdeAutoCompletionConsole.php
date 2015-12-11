<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Setup\Communication\Console;

use Spryker\Shared\Library\Config;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Zed\Console\Business\Model\Console;
use Spryker\Zed\Kernel\BundleNameFinder;
use Spryker\Zed\Kernel\IdeAutoCompletion\IdeAutoCompletionGenerator;
use Spryker\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\GeneratedInterfaceMethodTagBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateYvesIdeAutoCompletionConsole extends Console
{

    const COMMAND_NAME = 'setup:generate-yves-ide-auto-completion';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('This Command will generate the bundle ide auto completion interface for Yves.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->generateYvesInterface();
    }

    /**
     * @return void
     */
    protected function generateYvesInterface()
    {
        $options = $this->getYvesDefaultOptions();

        $generator = new IdeAutoCompletionGenerator($options, $this);
        $generator
            ->addMethodTagBuilder(new GeneratedInterfaceMethodTagBuilder(
                [
                    GeneratedInterfaceMethodTagBuilder::OPTION_METHOD_STRING_PATTERN => ' * @method \\Generated\Yves\Ide\{{bundle}} {{methodName}}()',
                ]
            ));

        $generator->create();

        $this->info('Generated Yves IdeAutoCompletion file');
    }

    /**
     * @return array
     */
    protected function getYvesDefaultOptions()
    {
        $options = [
            IdeAutoCompletionGenerator::OPTION_KEY_NAMESPACE => 'Generated\Yves\Ide',
            IdeAutoCompletionGenerator::OPTION_KEY_LOCATION_DIR => APPLICATION_SOURCE_DIR . '/Generated/Yves/Ide/',
            IdeAutoCompletionGenerator::OPTION_KEY_APPLICATION => 'Yves',
            IdeAutoCompletionGenerator::OPTION_KEY_BUNDLE_NAME_FINDER => new BundleNameFinder(
                [
                    IdeAutoCompletionGenerator::OPTION_KEY_APPLICATION => '*',
                    BundleNameFinder::OPTION_KEY_BUNDLE_PROJECT_PATH_PATTERN => $this->getProjectNamespace() . '/',
                ]
            ),
        ];

        return $options;
    }

    /**
     * @throws \Exception
     *
     * @return string
     */
    private function getProjectNamespace()
    {
        return Config::get(ApplicationConstants::PROJECT_NAMESPACES)[0];
    }

}
