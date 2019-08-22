<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\SchemaParser;

use SimpleXMLElement;
use Spryker\Zed\Development\Business\Exception\Dependency\PropelSchemaParserException;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class PropelSchemaParser implements PropelSchemaParserInterface
{
    protected const PROPEL_SCHEMA_PATH_PATTERN = '*/src/%s/Zed/*/Persistence/Propel/Schema';

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @var string[]
     */
    protected static $idFieldToModuleNameMap;

    /**
     * @var string[]
     */
    protected static $uniqueFieldToModuleNameMap;

    /**
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     */
    public function __construct(DevelopmentConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @return array
     */
    public function getForeignColumnNames(SplFileInfo $fileInfo): array
    {
        $foreignReferenceColumnNames = [];

        $simpleXmlElement = simplexml_load_file($fileInfo->getPathname());
        $hasNamespace = $this->hasNamespaceInSchema($simpleXmlElement);
        if ($hasNamespace) {
            $simpleXmlElement->registerXPathNamespace('s', 'spryker:schema-01');
        }
        $foreignReferences = $simpleXmlElement->xpath($hasNamespace ? '//s:table/s:foreign-key/s:reference' : '//table/foreign-key/reference');
        foreach ($foreignReferences as $foreignReference) {
            $parentNode = $foreignReference->xpath('parent::*')[0];
            $foreignTableName = (string)$parentNode['foreignTable'];
            $foreignReferenceName = (string)$foreignReference['foreign'];
            $foreignReferenceColumnNames[] = $foreignTableName . '.' . $foreignReferenceName;
        }

        return $foreignReferenceColumnNames;
    }

    /**
     * @param string $foreignReferenceColumnName
     * @param string $module
     *
     * @throws \Spryker\Zed\Development\Business\Exception\Dependency\PropelSchemaParserException
     *
     * @return string
     */
    public function getModuleNameByForeignReference(string $foreignReferenceColumnName, string $module): string
    {
        $idFieldToModuleNameMap = $this->getIdFieldToModuleNameMap();

        if (isset($idFieldToModuleNameMap[$foreignReferenceColumnName])) {
            return $idFieldToModuleNameMap[$foreignReferenceColumnName];
        }

        $uniqueFieldToModuleNameMap = $this->getUniqueFieldToModuleNameMap();

        if (isset($uniqueFieldToModuleNameMap[$foreignReferenceColumnName])) {
            return $uniqueFieldToModuleNameMap[$foreignReferenceColumnName];
        }

        throw new PropelSchemaParserException(sprintf('Could not find a module which defines the reference column "%s" defined in the module "%s"', $foreignReferenceColumnName, $module));
    }

    /**
     * @return array
     */
    protected function getIdFieldToModuleNameMap(): array
    {
        if (static::$idFieldToModuleNameMap === null) {
            static::$idFieldToModuleNameMap = $this->buildIdFieldToModuleNameMap();
        }

        return static::$idFieldToModuleNameMap;
    }

    /**
     * @return array
     */
    protected function getUniqueFieldToModuleNameMap(): array
    {
        if (static::$uniqueFieldToModuleNameMap === null) {
            static::$uniqueFieldToModuleNameMap = $this->buildUniqueFieldToModuleNameMap();
        }

        return static::$uniqueFieldToModuleNameMap;
    }

    /**
     * @return array
     */
    protected function buildIdFieldToModuleNameMap(): array
    {
        $idFieldToModuleNameMap = [];
        foreach ($this->getSchemaFileFinder() as $splFileInfo) {
            $module = $this->getModuleNameFromFile($splFileInfo);
            $idColumnNames = $this->getIdColumnNames($splFileInfo);
            $idFieldToModuleNameMap = $this->addIdColumnNames($idFieldToModuleNameMap, $idColumnNames, $module);
        }

        return $idFieldToModuleNameMap;
    }

    /**
     * @return array
     */
    protected function buildUniqueFieldToModuleNameMap(): array
    {
        $uniqueFieldToModuleNameMap = [];
        foreach ($this->getSchemaFileFinder() as $splFileInfo) {
            $module = $this->getModuleNameFromFile($splFileInfo);
            $uniqueColumnNames = $this->getUniqueColumnNames($splFileInfo);
            $requiredColumnNames = $this->getRequiredColumnNames($splFileInfo);
            $uniqueFieldToModuleNameMap = $this->addUniqueColumnNames($uniqueFieldToModuleNameMap, $requiredColumnNames, $uniqueColumnNames, $module);
        }

        return $uniqueFieldToModuleNameMap;
    }

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    protected function getSchemaFileFinder(): Finder
    {
        $finder = new Finder();
        $finder
            ->in($this->computeLookupPaths())
            ->name('*.schema.xml');

        return $finder;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @return string[]
     */
    protected function getIdColumnNames(SplFileInfo $splFileInfo): array
    {
        $simpleXmlElement = simplexml_load_file($splFileInfo->getPathname());

        $hasNamespace = $this->hasNamespaceInSchema($simpleXmlElement);
        if ($hasNamespace) {
            $simpleXmlElement->registerXPathNamespace('s', 'spryker:schema-01');
        }

        $idColumnNames = [];

        foreach ($simpleXmlElement->xpath($hasNamespace ? '//s:table' : '//table') as $simpleXmlTableElement) {
            $tableName = (string)$simpleXmlTableElement['name'];
            if ($hasNamespace) {
                $simpleXmlTableElement->registerXPathNamespace('s', 'spryker:schema-01');
            }
            $idColumnSimpleXmlElements = $simpleXmlTableElement->xpath($hasNamespace ? '//s:table[@name="' . $tableName . '"]/s:column[starts-with(@name, "id_")]' : '//table[@name="' . $tableName . '"]/column[starts-with(@name, "id_")]');
            if ($idColumnSimpleXmlElements === false) {
                continue;
            }

            foreach ($idColumnSimpleXmlElements as $idColumnSimpleXmlElement) {
                $idColumnName = (string)$idColumnSimpleXmlElement['name'];
                $idColumnNames[] = $tableName . '.' . $idColumnName;
            }
        }

        return $idColumnNames;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @return string[]
     */
    protected function getUniqueColumnNames(SplFileInfo $splFileInfo): array
    {
        $simpleXmlElement = simplexml_load_file($splFileInfo->getPathname());

        $hasNamespace = $this->hasNamespaceInSchema($simpleXmlElement);
        if ($hasNamespace) {
            $simpleXmlElement->registerXPathNamespace('s', 'spryker:schema-01');
        }

        $uniqueColumnNames = [];

        foreach ($simpleXmlElement->xpath($hasNamespace ? '//s:table' : '//table') as $simpleXmlTableElement) {
            $tableName = (string)$simpleXmlTableElement['name'];
            if ($hasNamespace) {
                $simpleXmlTableElement->registerXPathNamespace('s', 'spryker:schema-01');
            }
            $uniqueColumnSimpleXmlElements = $simpleXmlTableElement->xpath($hasNamespace ? '//s:table[@name="' . $tableName . '"]/s:unique/s:unique-column' : '//table[@name="' . $tableName . '"]/unique/unique-column');
            if ($uniqueColumnSimpleXmlElements === false) {
                continue;
            }

            foreach ($uniqueColumnSimpleXmlElements as $uniqueColumnSimpleXmlElement) {
                $uniqueColumnName = (string)$uniqueColumnSimpleXmlElement['name'];
                $uniqueColumnNames[] = $tableName . '.' . $uniqueColumnName;
            }
        }

        return $uniqueColumnNames;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @return string[]
     */
    protected function getRequiredColumnNames(SplFileInfo $splFileInfo): array
    {
        $simpleXmlElement = simplexml_load_file($splFileInfo->getPathname());

        $requiredColumnNames = [];

        foreach ($simpleXmlElement->xpath('//table') as $simpleXmlTableElement) {
            $tableName = (string)$simpleXmlTableElement['name'];
            $columnSimpleXmlElements = $simpleXmlTableElement->xpath('//table[@name="' . $tableName . '"]/column');
            if ($columnSimpleXmlElements === false) {
                continue;
            }

            foreach ($columnSimpleXmlElements as $columnSimpleXmlElement) {
                if ((string)$columnSimpleXmlElement['required'] === 'true') {
                    $requiredColumnName = (string)$columnSimpleXmlElement['name'];
                    $requiredColumnNames[] = $tableName . '.' . $requiredColumnName;
                }
            }
        }

        return $requiredColumnNames;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @return string
     */
    protected function getModuleNameFromFile(SplFileInfo $splFileInfo): string
    {
        preg_match('#Zed\/(?<module>[[:alpha:]]+)\/Persistence#', $splFileInfo->getPath(), $matches);

        return $matches['module'];
    }

    /**
     * @param array $idFieldToModuleNameMap
     * @param array $idColumnNames
     * @param string $module
     *
     * @throws \Spryker\Zed\Development\Business\Exception\Dependency\PropelSchemaParserException
     *
     * @return string[]
     */
    protected function addIdColumnNames(array $idFieldToModuleNameMap, array $idColumnNames, string $module): array
    {
        foreach ($idColumnNames as $idColumnName) {
            if (isset($idFieldToModuleNameMap[$idColumnName]) && $idFieldToModuleNameMap[$idColumnName] !== $module) {
                throw new PropelSchemaParserException(sprintf('Id column "%s" was already found. It is defined in the module "%s".', $idColumnName, $idFieldToModuleNameMap[$idColumnName]));
            }
            $idFieldToModuleNameMap[$idColumnName] = $module;
        }

        return $idFieldToModuleNameMap;
    }

    /**
     * @param array $uniqueFieldToModuleNameMap
     * @param string[] $requiredColumnNames
     * @param string[] $uniqueColumnNames
     * @param string $module
     *
     * @throws \Spryker\Zed\Development\Business\Exception\Dependency\PropelSchemaParserException
     *
     * @return string[]
     */
    protected function addUniqueColumnNames(array $uniqueFieldToModuleNameMap, array $requiredColumnNames, array $uniqueColumnNames, string $module): array
    {
        foreach ($uniqueColumnNames as $uniqueColumnName) {
            if (isset($uniqueFieldToModuleNameMap[$uniqueColumnName]) && $uniqueFieldToModuleNameMap[$uniqueColumnName] !== $module &&
                in_array($module . '.' . $uniqueColumnName, $requiredColumnNames)
            ) {
                throw new PropelSchemaParserException(sprintf('Unique column "%s" was already found in the module "%s".', $uniqueColumnName, $uniqueFieldToModuleNameMap[$uniqueColumnName]));
            }
            $uniqueFieldToModuleNameMap[$uniqueColumnName] = $module;
        }

        return $uniqueFieldToModuleNameMap;
    }

    /**
     * @return string[]
     */
    protected function computeLookupPaths(): array
    {
        $lookupPaths = $this->config->getOrganizationPathMap();

        foreach ($lookupPaths as $organizationName => $path) {
            $lookupPaths[$organizationName] = $path . sprintf(static::PROPEL_SCHEMA_PATH_PATTERN, $organizationName);
        }

        return array_filter($lookupPaths, 'glob');
    }

    /**
     * @param \SimpleXMLElement $simpleXmlElement
     *
     * @return bool
     */
    protected function hasNamespaceInSchema(SimpleXMLElement $simpleXmlElement): bool
    {
        if (in_array('spryker:schema-01', $simpleXmlElement->getNamespaces())) {
            return true;
        }

        return false;
    }
}
