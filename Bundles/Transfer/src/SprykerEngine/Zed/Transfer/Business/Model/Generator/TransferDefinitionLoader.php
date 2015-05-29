<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Zend\Config\Factory;
use Zend\Filter\Word\UnderscoreToCamelCase;

class TransferDefinitionLoader
{

    /**
     * @var DefinitionNormalizer
     */
    private $definitionNormalizer;

    /**
     * @var array
     */
    private $sourceDirectories;

    /**
     * @var array
     */
    private $transferDefinitions = [];

    /**
     * @param DefinitionNormalizer $normalizer
     * @param array $sourceDirectories
     */
    public function __construct(DefinitionNormalizer $normalizer, array $sourceDirectories)
    {
        $this->definitionNormalizer = $normalizer;
        $this->sourceDirectories = $sourceDirectories;
    }

    /**
     * @return array
     */
    public function getDefinitions()
    {
        $this->loadDefinitions($this->sourceDirectories);
        $this->transferDefinitions = $this->definitionNormalizer->normalizeDefinitions(
            $this->transferDefinitions
        );

        return $this->transferDefinitions;
    }

    /**
     * @param array $sourceDirectories
     *
     * @return array
     */
    private function loadDefinitions(array $sourceDirectories)
    {
        $xmlTransferDefinitions = $this->getXmlTransferDefinitionFiles($sourceDirectories);
        foreach ($xmlTransferDefinitions as $xmlTransferDefinition) {
            $bundle = $this->getBundleFromPathName($xmlTransferDefinition->getFilename());
            $definition = Factory::fromFile($xmlTransferDefinition->getPathname(), true)->toArray();
            $this->addDefinition($definition, $bundle);
        }
    }

    /**
     * @param array $sourceDirectories
     *
     * @return Finder|SplFileInfo[]
     */
    private function getXmlTransferDefinitionFiles(array $sourceDirectories)
    {
        $finder = new Finder();

        $finder->in($sourceDirectories)->name('*.transfer.xml')->depth('< 1');

        return $finder;
    }

    /**
     * @param $fileName
     * @return string
     */
    private function getBundleFromPathName($fileName)
    {
        $filter = new UnderscoreToCamelCase();

        return $filter->filter(str_replace('.transfer.xml', '', $fileName));
    }

    /**
     * @param array $definition
     * @param string $bundle
     */
    private function addDefinition(array $definition, $bundle)
    {
        if (isset($definition['transfer'][0])) {
            foreach ($definition['transfer'] as $transfer) {
                $transfer['bundle'] = $bundle;
                $this->transferDefinitions[] = $transfer;
            }
        } else {
            $transfer = $definition['transfer'];
            $transfer['bundle'] = $bundle;
            $this->transferDefinitions[] = $transfer;
        }
    }
}
