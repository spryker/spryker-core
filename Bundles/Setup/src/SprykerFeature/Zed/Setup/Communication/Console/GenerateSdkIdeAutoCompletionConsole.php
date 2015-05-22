<?php

namespace SprykerFeature\Zed\Setup\Communication\Console;

use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Zed\Console\Business\Model\Console;
use SprykerEngine\Zed\Kernel\BundleNameFinder;
use SprykerEngine\Zed\Kernel\IdeAutoCompletion\IdeAutoCompletionGenerator;
use SprykerEngine\Zed\Kernel\IdeAutoCompletion\IdeBundleAutoCompletionGenerator;
use SprykerEngine\Zed\Kernel\IdeAutoCompletion\IdeFactoryAutoCompletionGenerator;
use SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\ClientMethodTagBuilder;
use SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\ConstructableMethodTagBuilder;
use SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\GeneratedInterfaceMethodTagBuilder;
use SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\SdkMethodTagBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateSdkIdeAutoCompletionConsole extends Console
{

    const COMMAND_NAME = 'setup:generate-sdk-ide-auto-completion';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('This Command will generate the bundle ide auto completion interface for the Sdk.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->generateSdkInterface();
        $this->generateSdkBundleInterface();
        $this->generateSdkFactoryInterface();
    }

    protected function generateSdkInterface()
    {
        $options = $this->getSdkDefaultOptions();

        $generator = new IdeAutoCompletionGenerator($options, $this);
        $generator
            ->addMethodTagBuilder(new GeneratedInterfaceMethodTagBuilder(
                [
                    GeneratedInterfaceMethodTagBuilder::OPTION_METHOD_STRING_PATTERN =>
                        ' * @method \\Generated\Sdk\Ide\{{bundle}} {{methodName}}()'
                ]
            ))
        ;
        $generator->create();

        $this->info('Generated Sdk IdeAutoCompletion file');
    }

    /**
     * @return array
     */
    protected function getSdkDefaultOptions()
    {
        $options = [
            IdeAutoCompletionGenerator::OPTION_KEY_NAMESPACE => 'Generated\Sdk\Ide',
            IdeAutoCompletionGenerator::OPTION_KEY_LOCATION_DIR => APPLICATION_SOURCE_DIR . '/Generated/Sdk/Ide',
            IdeAutoCompletionGenerator::OPTION_KEY_APPLICATION => 'Sdk',
            IdeAutoCompletionGenerator::OPTION_KEY_BUNDLE_NAME_FINDER => new BundleNameFinder(
                [
                    BundleNameFinder::OPTION_KEY_APPLICATION => 'Sdk',
                    BundleNameFinder::OPTION_KEY_BUNDLE_PROJECT_PATH_PATTERN =>
                        $this->getProjectNamespace() . '/',
                ]
            ),
        ];

        return $options;
    }

    protected function generateSdkBundleInterface()
    {
        $options = $this->getSdkDefaultOptions();
        $options[IdeBundleAutoCompletionGenerator::OPTION_KEY_INTERFACE_NAME] = 'BundleAutoCompletion';

        $generator = new IdeBundleAutoCompletionGenerator($options);
        $generator
            ->addMethodTagBuilder(new SdkMethodTagBuilder())
            ->addMethodTagBuilder(new ClientMethodTagBuilder())
        ;

        $generator->create();

        $this->info('Generated Sdk IdeBundleAutoCompletion file');
    }

    protected function generateSdkFactoryInterface()
    {
        $methodTagGenerator = new ConstructableMethodTagBuilder([
            ConstructableMethodTagBuilder::OPTION_KEY_PATH_PATTERN => '',
            ConstructableMethodTagBuilder::OPTION_KEY_APPLICATION => 'Sdk',
            ConstructableMethodTagBuilder::OPTION_KEY_CLASS_NAME_PART_LEVEL => 3,
        ]);

        $options = [
            IdeFactoryAutoCompletionGenerator::OPTION_KEY_NAMESPACE => 'Generated\Sdk\Ide\FactoryAutoCompletion',
            IdeFactoryAutoCompletionGenerator::OPTION_KEY_LOCATION_DIR =>
                APPLICATION_SOURCE_DIR . '/Generated/Sdk/Ide',
            IdeFactoryAutoCompletionGenerator::OPTION_KEY_HAS_LAYERS => false,
            IdeFactoryAutoCompletionGenerator::OPTION_KEY_APPLICATION => 'Sdk',
            IdeFactoryAutoCompletionGenerator::OPTION_KEY_BUNDLE_NAME_FINDER => new BundleNameFinder(
                [
                    IdeFactoryAutoCompletionGenerator::OPTION_KEY_APPLICATION => 'Sdk',
                    BundleNameFinder::OPTION_KEY_BUNDLE_PROJECT_PATH_PATTERN => $this->getProjectNamespace() . '/',
                ]
            ),
        ];

        $generator = new IdeFactoryAutoCompletionGenerator($options);
        $generator->addMethodTagBuilder($methodTagGenerator);

        $generator->create();

        $this->info('Generated Sdk IdeFactoryAutoCompletion file');
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function getProjectNamespace()
    {
        return Config::get(SystemConfig::PROJECT_NAMESPACES)[0];
    }
}
