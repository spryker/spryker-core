<?php

namespace SprykerEngine\Zed\Transfer\Communication\Console;

use SprykerEngine\Zed\Transfer\Business\Model\Generator\ClassDefinition;
use SprykerFeature\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\ClassCollectionManager;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\ClassGenerator;
use Symfony\Component\Finder\SplFileInfo;
use Zend\Config\Factory;

class GeneratorConsole extends Console
{

    const COMMAND_NAME = 'transfer:generate';
    const TRANSFER_DEFINITION_FILE_NAME = 'transferDefinition.xml';

    /**
     * @var string
     */
    private $targetDirectory;

    protected function configure()
    {
        parent::configure();
        $this
            ->setName(self::COMMAND_NAME)
            ->setHelp('<info>' . self::COMMAND_NAME . ' -h</info>')
        ;

        $this->targetDirectory = APPLICATION_SOURCE_DIR . 'Generated/Shared/Transfer/';
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->removeGeneratedTransferObjects($this->targetDirectory);
        $this->createTransferObjects();
    }

    private function createTransferObjects()
    {
        $definitions = $this->getTransferDefinitions();
        $generator = $this->getClassGenerator();

        foreach ($definitions as $classDefinition) {
            $phpCode = $generator->generateClass($classDefinition);
            if (!is_dir($generator->getTargetFolder())) {
                mkdir($generator->getTargetFolder(), 0755, true);
            }

            file_put_contents($generator->getTargetFolder() . $classDefinition->getClassName() . '.php', $phpCode);
            $this->info(sprintf('<info>%s.php</info> was generated', $classDefinition->getClassName()));
        }
    }

    /**
     * @return array|ClassDefinition[]
     */
    private function getTransferDefinitions()
    {
        $manager = new ClassCollectionManager();

        $xmlDefinitions = $this->getXmlDefinitions();

        foreach ($xmlDefinitions as $config) {
            if (isset($config['transfer'][0])) {
                foreach ($config['transfer'] as $transferList) {
                    $manager->setClassDefinition($transferList);
                }
            } else {
                $manager->setClassDefinition($config['transfer']);
            }
        }

        return $manager->getCollections();
    }

    /**
     * @return array
     */
    private function getXmlDefinitions()
    {
        $xmlTransferDefinitions = $this->getXmlTransferDefinitionFiles();
        $xmlTransferDefinitionList = [];
        foreach ($xmlTransferDefinitions as $xmlTransferDefinition) {
            $xmlTransferDefinitionList[] = Factory::fromFile($xmlTransferDefinition->getPathname(), true)->toArray();
        }

        return $xmlTransferDefinitionList;
    }

    /**
     * remove generated TransferObjects
     */
    private function removeGeneratedTransferObjects()
    {
        foreach ($this->getGeneratedFile() as $file) {
            unlink($file->getRealPath());
        }
    }

    /**
     * @return Finder|SplFileInfo[]
     */
    private function getGeneratedFile()
    {
        $finder = new Finder();
        $finder->files()->in($this->targetDirectory);

        return $finder;
    }

    /**
     * @return Finder|SplFileInfo[]
     */
    private function getXmlTransferDefinitionFiles()
    {
        $finder = new Finder();
        $directories = [
            APPLICATION_VENDOR_DIR . '/*/*/src/*/Shared/*/Transfer/',
        ];

        if (glob(APPLICATION_SOURCE_DIR . '/*/Shared/*/Transfer/')) {
            $directories[] = APPLICATION_SOURCE_DIR . '/*/Shared/*/Transfer/';
        }

        $finder->in($directories)->name(self::TRANSFER_DEFINITION_FILE_NAME);

        return $finder;
    }

    /**
     * @return ClassGenerator
     */
    private function getClassGenerator()
    {
        $generator = new ClassGenerator();
        $generator->setNamespace('Generated\Shared\Transfer');
        $generator->setTargetFolder($this->targetDirectory);
        return $generator;
    }

}
