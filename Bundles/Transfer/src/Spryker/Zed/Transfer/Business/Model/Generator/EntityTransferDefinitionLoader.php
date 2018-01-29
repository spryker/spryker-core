<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

use Zend\Filter\FilterChain;
use Zend\Filter\Word\DashToCamelCase;
use Zend\Filter\Word\UnderscoreToCamelCase;
use Zend\Config\Factory;

class EntityTransferDefinitionLoader extends TransferDefinitionLoader
{
    const KEY_TABLE = 'table';
    const ENTITY_SCHEMA_SUFFIX = '.schema.xml';
    const ENTITY_PREFIX = 'spy_';
    const PREFIX_LENGTH = 4;
    const ENTITY_NAMESPACE = 'entity-namespace';

    /**
     * @return array
     */
    protected function loadDefinitions()
    {
        $xmlTransferDefinitions = $this->finder->getXmlTransferDefinitionFiles();
        foreach ($xmlTransferDefinitions as $xmlTransferDefinition) {

            $xml = simplexml_load_string($xmlTransferDefinition->getContents());
            $namespace = (string)$xml['namespace'];

            $containingBundle = $this->getContainingBundleFromPathName($xmlTransferDefinition->getPathname());
            $definition = Factory::fromFile($xmlTransferDefinition->getPathname(), true)->toArray();
            $definition[self::ENTITY_NAMESPACE] = $namespace;

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

                $this->transferDefinitions[] = $table;
            }
        } else {
            $table = $definition[static::KEY_TABLE];

            $table[self::KEY_BUNDLE] = $module;
            $table[self::KEY_CONTAINING_BUNDLE] = $containingBundle;
            $table[self::ENTITY_NAMESPACE] = $definition[self::ENTITY_NAMESPACE];
            $this->transferDefinitions[] = $table;
        }
    }
}
