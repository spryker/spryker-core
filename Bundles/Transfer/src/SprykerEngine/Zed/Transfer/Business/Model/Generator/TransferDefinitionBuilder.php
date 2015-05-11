<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Zend\Config\Factory;

class TransferDefinitionBuilder
{

    /**
     * @var array
     */
    private $sourceDirectories;

    /**
     * @var array
     */
    private $transferDefinitions = [];

    /**
     * @var array
     */
    private $mergedTransferDefinitions = [];

    /**
     * @var TransferDefinitionMerger
     */
    private $transferDefinitionMerger;

    /**
     * @param TransferDefinitionMerger $merger
     * @param array $sourceDirectories
     */
    public function __construct(TransferDefinitionMerger $merger, array $sourceDirectories)
    {
        $this->transferDefinitionMerger = $merger;
        $this->sourceDirectories = $sourceDirectories;
    }

    /**
     * @return ClassDefinition[]
     */
    public function getTransferDefinitions()
    {
        $this->loadTransferDefinitions();
        $this->mergeTransferDefinitions();

        return $this->getClassDefinitions();
    }

    private function loadTransferDefinitions()
    {
        $xmlDefinitions = $this->readXmlDefinitions();

        foreach ($xmlDefinitions as $config) {
            if (isset($config['transfer'][0])) {
                foreach ($config['transfer'] as $transfer) {
                    $this->transferDefinitions[] = $transfer;
                }
            } else {
                $this->transferDefinitions[] = $config['transfer'];
            }
        }
    }

    /**
     * @return array
     */
    private function readXmlDefinitions()
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

    private function mergeTransferDefinitions()
    {
        $this->mergedTransferDefinitions = $this->transferDefinitionMerger->merge($this->transferDefinitions);
    }

    /**
     * @return ClassDefinition[]
     */
    private function getClassDefinitions()
    {
        $transferClassDefinitions = [];
        foreach ($this->mergedTransferDefinitions as $transferDefinition) {
            $transferClassDefinitions[$transferDefinition['name']] = new ClassDefinition($transferDefinition);
        }

        return $transferClassDefinitions;
    }
}
