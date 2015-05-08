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

    /**
     * @var ClassGenerator
     */
    private $classGenerator;

    /**
     * @var array
     */
    private $sourceDirectories;

    /**
     * @var string
     */
    private $targetDirectory;

    /**
     * @param MessengerInterface $messenger
     * @param ClassGenerator $classGenerator
     * @param array $sourceDirectories
     * @param string $targetDirectory
     */
    public function __construct(MessengerInterface $messenger, ClassGenerator $classGenerator, array $sourceDirectories, $targetDirectory)
    {
        $this->messenger = $messenger;
        $this->classGenerator = $classGenerator;
        $this->sourceDirectories = $sourceDirectories;
        $this->targetDirectory = $targetDirectory;
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
            $phpCode = $this->classGenerator->generateClass($classDefinition);

            file_put_contents($this->targetDirectory . $classDefinition->getName() . '.php', $phpCode);
            $this->messenger->info(sprintf('<info>%s.php</info> was generated', $classDefinition->getName()));
        }
    }

    /**
     * @return ClassDefinition[]
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
        foreach ($this->getGeneratedFiles() as $file) {
            unlink($file->getRealPath());
        }
    }

    /**
     * @return Finder|SplFileInfo[]
     */
    private function getGeneratedFiles()
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

        $finder->in($this->sourceDirectories)->name('*.transfer.xml');

        return $finder;
    }
}
