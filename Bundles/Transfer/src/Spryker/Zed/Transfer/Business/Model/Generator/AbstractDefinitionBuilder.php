<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

abstract class AbstractDefinitionBuilder implements DefinitionBuilderInterface
{

    /**
     * @param array $definitions
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\DefinitionInterface $definitionClass
     *
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\DefinitionInterface[]
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
