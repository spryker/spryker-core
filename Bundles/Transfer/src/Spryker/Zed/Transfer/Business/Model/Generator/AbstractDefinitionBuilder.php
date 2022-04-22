<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

abstract class AbstractDefinitionBuilder implements DefinitionBuilderInterface
{
    /**
     * @param array $definitions
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\DefinitionInterface $definitionClass
     *
     * @return array<\Spryker\Zed\Transfer\Business\Model\Generator\DefinitionInterface>
     */
    protected function buildDefinitions(array $definitions, DefinitionInterface $definitionClass): array
    {
        $definitionInstances = [];
        foreach ($definitions as $definition) {
            $definitionInstance = clone $definitionClass;
            $definitionInstances[] = $definitionInstance->setDefinition($definition);
        }

        return $definitionInstances;
    }
}
