<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferInterface;

use SprykerEngine\Zed\Transfer\Business\Model\Generator\AbstractDefinitionBuilder;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferDefinitionLoader;

class TransferInterfaceDefinitionBuilder extends AbstractDefinitionBuilder
{
    /**
     * @var array
     */
    private $definitions;

    /**
     * @var InterfaceDefinition
     */
    private $interfaceDefinition;

    /**
     * @param TransferDefinitionLoader $loader
     * @param InterfaceDefinition $interfaceDefinition
     */
    public function __construct(TransferDefinitionLoader $loader, InterfaceDefinition $interfaceDefinition)
    {
        $this->definitions = $loader->getDefinitions();
        $this->interfaceDefinition = $interfaceDefinition;
    }

    /**
     * @return InterfaceDefinition[]
     */
    public function getDefinitions()
    {
        return $this->buildDefinitions($this->definitions, $this->interfaceDefinition);
    }
}
