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
     * @param MessengerInterface $messenger
     * @param ClassGenerator $classGenerator
     * @param array $sourceDirectories
     */
    public function __construct(MessengerInterface $messenger, ClassGenerator $classGenerator, array $sourceDirectories)
    {
        $this->messenger = $messenger;
        $this->classGenerator = $classGenerator;
        $this->sourceDirectories = $sourceDirectories;
    }

    public function execute()
    {
        $this->createTransferObjects();
    }

    private function createTransferObjects()
    {
        $definitions = $this->getTransferDefinitions();

        foreach ($definitions as $classDefinition) {
            $fileName = $this->classGenerator->generateClass($classDefinition);

            $this->messenger->info(sprintf('<info>%s.php</info> was generated', $fileName));
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
     * @return Finder|SplFileInfo[]
     */
    private function getXmlTransferDefinitionFiles()
    {
        $finder = new Finder();

        $finder->in($this->sourceDirectories)->name('*.transfer.xml');

        return $finder;
    }
}
