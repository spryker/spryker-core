<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferInterface;

use SprykerEngine\Zed\Transfer\Business\Model\Generator\AbstractDefinitionBuilder;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferDefinitionLoader;

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
     * @param TransferDefinitionLoader $loader
     * @param InterfaceDefinition $interfaceDefinition
     */
    public function __construct(TransferDefinitionLoader $loader, InterfaceDefinition $interfaceDefinition)
    {
        $this->loader = $loader;
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
}
