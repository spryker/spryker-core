<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferInterface;

use SprykerEngine\Zed\Transfer\Business\Model\Generator\AbstractDefinitionBuilder;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferDefinitionLoader;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferDefinitionMerger;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\DefinitionInterface;

/**
 * @todo remove merger code here
 */
class TransferInterfaceDefinitionBuilder extends AbstractDefinitionBuilder
{

    /**
     * @var TransferDefinitionLoader
     */
    private $loader;

    /**
     * @var InterfaceDefinition
     */
    private $interfaceDefinition;

    /**
     * @var TransferDefinitionMerger
     */
    private $merger;

    /**
     * @param TransferDefinitionLoader $loader
     * @param TransferDefinitionMerger $merger
     * @param InterfaceDefinition $interfaceDefinition
     */
    public function __construct(TransferDefinitionLoader $loader, TransferDefinitionMerger $merger, InterfaceDefinition $interfaceDefinition)
    {
        $this->loader = $loader;
        $this->merger = $merger;
        $this->interfaceDefinition = $interfaceDefinition;
    }

    /**
     * @return InterfaceDefinition[]
     */
    public function getDefinitions()
    {
        $definitions = $this->loader->getDefinitions();
        
        return $this->buildDefinitions($definitions, $this->interfaceDefinition);
    }

    /**
     * @param array $definitions
     * @param DefinitionInterface $definitionClass
     *
     * @return DefinitionInterface[]
     */
    protected function buildDefinitions(array $definitions, DefinitionInterface $definitionClass)
    {
        $definitionInstances = [];
        foreach ($definitions as $definition) {
            if (!isset($definition['bundle']) && isset($definition['interface'])) {
                foreach ($definition['interface'] as $interfaceDefinition) {
                    $definitionInstance = clone $definitionClass;
                    $definition['bundle'] = $interfaceDefinition['bundle'];
                    $definitionInstances[] = $definitionInstance->setDefinition($definition);
                }
            } else {
                $definitionInstance = clone $definitionClass;
                $definitionInstances[] = $definitionInstance->setDefinition($definition);
            }
        }

        return $definitionInstances;
    }

}
