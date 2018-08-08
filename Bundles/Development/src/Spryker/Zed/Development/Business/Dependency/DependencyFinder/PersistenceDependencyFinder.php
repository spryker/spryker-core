<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyFinder;

use Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Development\Business\Dependency\SchemaParser\PropelSchemaParserInterface;

class PersistenceDependencyFinder implements DependencyFinderInterface
{
    public const TYPE_PERSISTENCE = 'persistence';

    /**
     * @var \Spryker\Zed\Development\Business\Dependency\SchemaParser\PropelSchemaParserInterface
     */
    protected $propelSchemaParser;

    /**
     * @param \Spryker\Zed\Development\Business\Dependency\SchemaParser\PropelSchemaParserInterface $propelSchemaParser
     */
    public function __construct(PropelSchemaParserInterface $propelSchemaParser)
    {
        $this->propelSchemaParser = $propelSchemaParser;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE_PERSISTENCE;
    }

    /**
     * @param string $module
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface $dependencyContainer
     * @param string|null $dependencyType
     *
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface
     */
    public function findDependencies(string $module, DependencyContainerInterface $dependencyContainer, ?string $dependencyType = null): DependencyContainerInterface
    {
        if ($dependencyType !== null && $dependencyType !== $this->getType()) {
            return $dependencyContainer;
        }

        $foreignIdColumnNames = $this->propelSchemaParser->getForeignColumnNames($module);

        foreach ($foreignIdColumnNames as $foreignIdColumnName) {
            $dependentModule = $this->propelSchemaParser->getModuleNameByForeignReference($foreignIdColumnName, $module);
            $dependencyContainer->addDependency(
                $dependentModule,
                $this->getType()
            );
        }

        return $dependencyContainer;
    }
}
