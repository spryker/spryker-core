<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer;

use SprykerEngine\Zed\Transfer\Business\Model\Generator\AbstractDefinitionBuilder;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferDefinitionLoader;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferDefinitionMerger;

class TransferDefinitionBuilder extends AbstractDefinitionBuilder
{

    /**
     * @var array
     */
    private $definitions;

    /**
     * @var ClassDefinition
     */
    private $classDefinition;

    /**
     * @param TransferDefinitionLoader $loader
     * @param TransferDefinitionMerger $merger
     * @param ClassDefinition $classDefinition
     */
    public function __construct(TransferDefinitionLoader $loader, TransferDefinitionMerger $merger, ClassDefinition $classDefinition)
    {
        $definitions = $loader->getDefinitions();
        $this->definitions = $merger->merge($definitions);
        $this->classDefinition = $classDefinition;
    }

    /**
     * @return ClassDefinition[]
     */
    public function getDefinitions()
    {
        return $this->buildDefinitions($this->definitions, $this->classDefinition);
    }
}
