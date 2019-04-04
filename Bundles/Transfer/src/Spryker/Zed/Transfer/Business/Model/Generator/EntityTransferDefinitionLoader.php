<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

use Spryker\Zed\Transfer\Business\Exception\EmptyEntityTransferDefinitionException;
use Zend\Config\Factory;

class EntityTransferDefinitionLoader extends TransferDefinitionLoader
{
    public const KEY_TABLE = 'table';
    public const KEY_COLUMN = 'column';
    public const KEY_NAME = 'name';
    public const ENTITY_SCHEMA_SUFFIX = '.schema.xml';
    public const ENTITY_PREFIX = 'spy_';
    public const PREFIX_LENGTH = 4;
    public const ENTITY_NAMESPACE = 'entity-namespace';
    public const ENTITY_SCHEMA_PATHNAME = 'path';

    /**
     * @return void
     */
    protected function loadDefinitions()
    {
        $xmlTransferDefinitions = $this->finder->getXmlTransferDefinitionFiles();
        foreach ($xmlTransferDefinitions as $xmlTransferDefinition) {
            $xml = simplexml_load_string($xmlTransferDefinition->getContents());
            $namespace = (string)$xml['namespace'];

            $transferDefinitionFilePath = $xmlTransferDefinition->getPathname();
            $containingBundle = $this->getContainingBundleFromPathName($transferDefinitionFilePath);
            $definition = Factory::fromFile($transferDefinitionFilePath, true)->toArray();
            $definition[self::ENTITY_NAMESPACE] = $namespace;
            $definition[static::ENTITY_SCHEMA_PATHNAME] = $transferDefinitionFilePath;

            $this->addDefinition($definition, '', $containingBundle);
        }
    }

    /**
     * @param array $definition
     * @param string $module
     * @param string $containingBundle
     *
     * @return void
     */
    protected function addDefinition(array $definition, $module, $containingBundle)
    {
        if (isset($definition[static::KEY_TABLE][0])) {
            foreach ($definition[static::KEY_TABLE] as $table) {
                $table[self::KEY_BUNDLE] = $module;
                $table[self::KEY_CONTAINING_BUNDLE] = $containingBundle;
                $table[self::ENTITY_NAMESPACE] = $definition[self::ENTITY_NAMESPACE];

                $this->assertDefinitionHasColumns($table, $definition[static::ENTITY_SCHEMA_PATHNAME]);
                $this->transferDefinitions[] = $table;
            }
        } else {
            $table = $definition[static::KEY_TABLE];

            $table[self::KEY_BUNDLE] = $module;
            $table[self::KEY_CONTAINING_BUNDLE] = $containingBundle;
            $table[self::ENTITY_NAMESPACE] = $definition[self::ENTITY_NAMESPACE];

            $this->assertDefinitionHasColumns($table, $definition[static::ENTITY_SCHEMA_PATHNAME]);
            $this->transferDefinitions[] = $table;
        }
    }

    /**
     * @param array $definition
     * @param string $transferDefinitionFilePath
     *
     * @throws \Spryker\Zed\Transfer\Business\Exception\EmptyEntityTransferDefinitionException
     *
     * @return void
     */
    protected function assertDefinitionHasColumns(array $definition, string $transferDefinitionFilePath): void
    {
        if (!isset($definition[static::KEY_COLUMN])) {
            throw new EmptyEntityTransferDefinitionException(
                sprintf(
                    'Schema definition file `%s` doesn\'t contain any column definition for `%s` table, please add at least one column to it.',
                    $transferDefinitionFilePath,
                    $definition[static::KEY_NAME]
                )
            );
        }
    }
}
