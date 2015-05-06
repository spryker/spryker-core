<?php

namespace SprykerEngine\Zed\Transfer\Business\Model;

use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\ClassDefinition;
use Symfony\Component\Finder\Finder;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\ClassCollectionManager;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\ClassGenerator;
use Symfony\Component\Finder\SplFileInfo;
use Zend\Config\Factory;

class TransferGenerator
{

    const TRANSFER_DEFINITION_FILE_NAME = 'transferDefinition.xml';

    /**
     * @var string
     */
    private $targetDirectory;

    /**
     * @param MessengerInterface $messenger
     */
    public function __construct(MessengerInterface $messenger)
    {
        $this->messenger = $messenger;
        $this->targetDirectory = APPLICATION_SOURCE_DIR . 'Generated/Shared/Transfer/';
    }

    public function execute()
    {
        if (is_dir($this->targetDirectory) === false) {
            mkdir($this->targetDirectory, 0777, true);
        }
        $this->removeGeneratedTransferObjects();
        $this->createTransferObjects();
    }

    private function createTransferObjects()
    {
        $definitions = $this->getTransferDefinitions();

        foreach ($definitions as $classDefinition) {
            $generator = $this->getClassGenerator();
            $phpCode = $generator->generateClass($classDefinition);

            file_put_contents($this->targetDirectory . $classDefinition->getName() . '.php', $phpCode);
            $this->messenger->info(sprintf('<info>%s.php</info> was generated', $classDefinition->getName()));
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
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src/*/Shared/*/Transfer/',
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
        $generator = new ClassGenerator($this->targetDirectory);

        return $generator;
    }
}
