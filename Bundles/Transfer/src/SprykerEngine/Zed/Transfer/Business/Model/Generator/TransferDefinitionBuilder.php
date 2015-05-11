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
     * @param array $sourceDirectories
     */
    public function __construct(array $sourceDirectories)
    {
        $this->sourceDirectories = $sourceDirectories;
    }

    /**
     * @return ClassDefinition[]
     */
    public function getTransferDefinitions()
    {
        $xmlDefinitions = $this->getXmlDefinitions();

        foreach ($xmlDefinitions as $config) {
            if (isset($config['transfer'][0])) {
                foreach ($config['transfer'] as $transfer) {
                    $this->addTransferDefinition($transfer);
                }
            } else {
                $this->addTransferDefinition($config['transfer']);
            }
        }

        return $this->transferDefinitions;
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

    /**
     * @param array $transfer
     */
    private function addTransferDefinition(array $transfer)
    {
        $this->transferDefinitions[$transfer['name']] = new ClassDefinition($transfer);
    }
}
