<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Propel;

use Exception;
use Psr\Log\LoggerInterface;
use SimpleXMLElement;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Zend\Filter\FilterChain;
use Zend\Filter\Word\UnderscoreToCamelCase;

class PropelAbstractClassValidator implements PropelAbstractClassValidatorInterface
{
    /**
     * @param \Psr\Log\LoggerInterface $messenger
     * @param null|string $module
     *
     * @return bool
     */
    public function validate(LoggerInterface $messenger, ?string $module): bool
    {
        if ($module === null) {
            return $this->validateModules($messenger);
        }

        return $this->validateModule($messenger, $module);
    }

    /**
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return bool
     */
    protected function validateModules(LoggerInterface $messenger): bool
    {
        $modules = $this->getModuleNames();
        $isValid = true;
        foreach ($modules as $module) {
            $isModuleValid = $this->validateModule($messenger, $module);
            if (!$isModuleValid) {
                $isValid = false;
            }
        }

        return $isValid;
    }

    /**
     * @return array
     */
    protected function getModuleNames(): array
    {
        $finder = new Finder();
        $finder->directories()->in(APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/')->depth('< 1');

        $modules = [];

        foreach ($finder as $directory) {
            $modules[] = $directory->getBasename();
        }

        asort($modules);

        return $modules;
    }

    /**
     * @param \Psr\Log\LoggerInterface $messenger
     * @param string $module
     *
     * @return bool
     */
    protected function validateModule(LoggerInterface $messenger, string $module): bool
    {
        if (!$this->hasSchemaDirectory($module)) {
            return true;
        }
        $moduleSchemaFileFinder = $this->getModuleSchemaFileFinder($module);

        $isModuleValid = true;

        foreach ($moduleSchemaFileFinder as $schemaFile) {
            $isValid = $this->abstractForTablesExist($messenger, $module, $schemaFile);
            if (!$isValid) {
                $isModuleValid = false;
            }
        }

        return $isModuleValid;
    }

    /**
     * @param string $module
     *
     * @return bool
     */
    protected function hasSchemaDirectory(string $module): bool
    {
        $pathToModuleSchema = $this->getPathToModuleSchemas($module);

        return is_dir($pathToModuleSchema);
    }

    /**
     * @param string $module
     *
     * @return \Symfony\Component\Finder\Finder
     */
    protected function getModuleSchemaFileFinder(string $module)
    {
        $finder = new Finder();
        $finder->in($this->getPathToModuleSchemas($module));

        return $finder;
    }

    /**
     * @param string $module
     *
     * @return string
     */
    protected function getPathToModuleSchemas(string $module): string
    {
        return sprintf('%1$s/spryker/spryker/Bundles/%2$s/src/Spryker/Zed/%2$s/Persistence/Propel/Schema/', APPLICATION_VENDOR_DIR, $module);
    }

    /**
     * @param \Psr\Log\LoggerInterface $messenger
     * @param string $module
     * @param \Symfony\Component\Finder\SplFileInfo $schemaFile
     *
     * @throws \Exception
     *
     * @return bool
     */
    protected function abstractForTablesExist(LoggerInterface $messenger, string $module, SplFileInfo $schemaFile): bool
    {
        $isValid = true;
        $simpleXmlElement = simplexml_load_file($schemaFile->getPathname());
        if ($simpleXmlElement === false) {
            throw new Exception('Could not load xml file');
        }

        $simpleXmlTableElements = $simpleXmlElement->xpath('//table');
        if ($simpleXmlTableElements === false) {
            throw new Exception('No table found in ');
        }

        $schemaModule = $this->getModuleFromSchemaNamespace($simpleXmlElement);
        if ($schemaModule !== $module) {
            $messenger->debug(sprintf('Current schema file found in "%s" belongs to "%s", validation skipped.', $module, $schemaModule));

            return $isValid;
        }

        foreach ($simpleXmlTableElements as $simpleXmlTableElement) {
            $phpName = $this->getPhpNameFromSimpleXmlTableElement($simpleXmlTableElement);
            $tableName = $this->getTableNameFromSimpleXmlTableElement($simpleXmlTableElement);

            $abstractEntityClass = sprintf('Spryker\\Zed\\%s\\Persistence\\Propel\\Abstract%s', $module, $phpName);
            if (!class_exists($abstractEntityClass)) {
                $isValid = false;
                $messenger->error(sprintf('%s does not exists, please create one.', $abstractEntityClass));
                $messenger->info(sprintf('vendor/bin/console spryk:run AddZedPersistencePropelAbstractEntity  --module=\'%1$s\' --targetModule=\'%1$s\' --tableName=\'%s\'', $module, $tableName));
            }

            $abstractQueryClass = sprintf('Spryker\\Zed\\%s\\Persistence\\Propel\\Abstract%sQuery', $module, $phpName);
            if (!class_exists($abstractQueryClass)) {
                $isValid = false;
                $messenger->error(sprintf('%s does not exists, please create one.', $abstractQueryClass));
                $messenger->info(sprintf('vendor/bin/console spryk:run AddZedPersistencePropelAbstractQuery  --module=\'%1$s\' --targetModule=\'%1$s\' --tableName=\'%s\'', $module, $tableName));

            }
        }

        return $isValid;
    }

    /**
     * @param \SimpleXMLElement $simpleXmlTableElement
     *
     * @return string
     */
    protected function getPhpNameFromSimpleXmlTableElement(SimpleXMLElement $simpleXmlTableElement): string
    {
        $phpName = (string)$simpleXmlTableElement['phpName'];
        if ($phpName === '') {
            $tableName = (string)$simpleXmlTableElement['name'];
            $phpName = $this->normalizeTableName($tableName);
        }

        return $phpName;
    }

    /**
     * @param \SimpleXMLElement $simpleXmlTableElement
     *
     * @return string
     */
    protected function getTableNameFromSimpleXmlTableElement(SimpleXMLElement $simpleXmlTableElement): string
    {
        return (string)$simpleXmlTableElement['name'];
    }

    /**
     * @param string $tableName
     *
     * @return string
     */
    protected function normalizeTableName(string $tableName): string
    {
        $filter = new FilterChain();
        $filter->attach(new UnderscoreToCamelCase());

        return $filter->filter($tableName);
    }

    /**
     * @param \SimpleXMLElement $simpleXmlElement
     *
     * @return string
     */
    protected function getModuleFromSchemaNamespace(SimpleXMLElement $simpleXmlElement): string
    {
        $namespace = (string)$simpleXmlElement['namespace'];
        $namespaceFragments = explode('\\', $namespace);
        $schemaModule = $namespaceFragments[2];

        return $schemaModule;
    }
}
