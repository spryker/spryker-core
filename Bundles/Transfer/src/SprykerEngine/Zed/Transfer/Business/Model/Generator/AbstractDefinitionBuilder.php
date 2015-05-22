<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;


abstract class AbstractDefinitionBuilder implements DefinitionBuilderInterface
{

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
            $definitionInstance = clone $definitionClass;
            $definitionInstances[] = $definitionInstance->setDefinition($definition);
        }

        return $definitionInstances;
    }
}
