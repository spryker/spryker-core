<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Propel;

use Exception;
use Laminas\Filter\FilterChain;
use Laminas\Filter\Word\UnderscoreToCamelCase;
use SimpleXMLElement;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class PropelAbstractClassValidator implements PropelAbstractClassValidatorInterface
{
    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string|null $module
     *
     * @return bool
     */
    public function validate(OutputInterface $output, ?string $module): bool
    {
        if ($module === null) {
            return $this->validateModules($output);
        }

        return $this->validateModule($output, $module);
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return bool
     */
    protected function validateModules(OutputInterface $output): bool
    {
        $modules = $this->getModuleNames();
        $isValid = true;
        foreach ($modules as $module) {
            $isModuleValid = $this->validateModule($output, $module);
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
        $finder->directories()->in([
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/',
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Features/',
        ])->depth('< 1');

        $modules = [];

        foreach ($finder as $directory) {
            $modules[] = $directory->getBasename();
        }

        asort($modules);

        return $modules;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $module
     *
     * @return bool
     */
    protected function validateModule(OutputInterface $output, string $module): bool
    {
        if (!$this->hasSchemaDirectory($module)) {
            return true;
        }
        $moduleSchemaFileFinder = $this->getModuleSchemaFileFinder($module);

        foreach ($moduleSchemaFileFinder as $schemaFile) {
            if (is_dir($schemaFile->getPathname())) {
                $moduleNestedSchemaFileFinder = new Finder();
                $moduleNestedSchemaFileFinder->in($schemaFile->getPathname());

                foreach ($moduleNestedSchemaFileFinder as $nestedSchemaFile) {
                    if (!$this->abstractClassesForTablesExist($output, $module, $nestedSchemaFile)) {
                        return false;
                    }
                }

                continue;
            }

            if (!$this->abstractClassesForTablesExist($output, $module, $schemaFile)) {
                return false;
            }
        }

        return true;
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
        $bundlePath = sprintf('%1$s/spryker/spryker/Bundles/%2$s/src/Spryker/Zed/%2$s/Persistence/Propel/Schema/', APPLICATION_VENDOR_DIR, $module);
        $featurePath = sprintf('%1$s/spryker/spryker/Features/%2$s/src/SprykerFeature/Zed/%2$s/Persistence/Propel/Schema/', APPLICATION_VENDOR_DIR, $module);

        return is_dir($featurePath) ? $featurePath : $bundlePath;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $module
     * @param \Symfony\Component\Finder\SplFileInfo $schemaFile
     *
     * @throws \Exception
     *
     * @return bool
     */
    protected function abstractClassesForTablesExist(OutputInterface $output, string $module, SplFileInfo $schemaFile): bool
    {
        $isValid = true;
        $simpleXmlElement = simplexml_load_file($schemaFile->getPathname());
        if ($simpleXmlElement === false) {
            throw new Exception('Could not load xml file');
        }

        $simpleXmlTableElements = $this->getSimpleXmlTableElements($simpleXmlElement);

        if (!$simpleXmlTableElements) {
            throw new Exception('No table found in ');
        }

        $schemaModule = $this->getModuleFromSchemaNamespace($simpleXmlElement);
        if ($schemaModule !== $module) {
            if ($output->isVerbose()) {
                $output->writeln(sprintf('<fg=yellow>Current schema file found in "<fg=green>%s</>" belongs to "<fg=green>%s</>", validation skipped.</>', $module, $schemaModule));
            }

            return $isValid;
        }

        return $this->abstractClassesForTableExists($simpleXmlTableElements, $module, $output);
    }

    /**
     * @param \SimpleXMLElement $simpleXmlElement
     *
     * @return bool
     */
    protected function hasNamespaceInSchema(SimpleXMLElement $simpleXmlElement): bool
    {
        if (in_array('spryker:schema-01', $simpleXmlElement->getNamespaces(), true)) {
            return true;
        }

        return false;
    }

    /**
     * @param array<\SimpleXMLElement> $simpleXmlTableElements
     * @param string $module
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return bool
     */
    protected function abstractClassesForTableExists(array $simpleXmlTableElements, string $module, OutputInterface $output): bool
    {
        $isValid = true;

        foreach ($simpleXmlTableElements as $simpleXmlTableElement) {
            $phpName = $this->getPhpNameFromSimpleXmlTableElement($simpleXmlTableElement);
            $tableName = $this->getTableNameFromSimpleXmlTableElement($simpleXmlTableElement);

            $abstractEntityClass = sprintf('Spryker\\Zed\\%s\\Persistence\\Propel\\Abstract%s', $module, $phpName);
            $abstractFeatureEntityClass = sprintf('SprykerFeature\\Zed\\%s\\Persistence\\Propel\\Abstract%s', $module, $phpName);
            if (!class_exists($abstractEntityClass) && !class_exists($abstractFeatureEntityClass)) {
                $isValid = false;
                $output->writeln(sprintf('<fg=red>Neither %s nor %s</> <fg=red>does not exists, please create one.</>', $abstractEntityClass, $abstractFeatureEntityClass));
                $output->writeln(sprintf('<fg=green>vendor/bin/spryk-run AddZedPersistencePropelAbstractEntity  --module=\'%1$s\' --targetModule=\'%1$s\' --tableName=\'%2$s\' -n</>', $module, $tableName));
            }

            $abstractQueryClass = sprintf('Spryker\\Zed\\%s\\Persistence\\Propel\\Abstract%sQuery', $module, $phpName);
            $abstractFeatureQueryClass = sprintf('SprykerFeature\\Zed\\%s\\Persistence\\Propel\\Abstract%sQuery', $module, $phpName);
            if (!class_exists($abstractQueryClass) && !class_exists($abstractFeatureQueryClass)) {
                $isValid = false;
                $output->writeln(sprintf('<fg=red>Neither %s nor %s does not exists, please create one.</>', $abstractQueryClass, $abstractFeatureQueryClass));
                $output->writeln(sprintf('<fg=green>vendor/bin/spryk-run AddZedPersistencePropelAbstractQuery  --module=\'%1$s\' --targetModule=\'%1$s\' --tableName=\'%2$s\' -n</>', $module, $tableName));
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

    /**
     * @param \SimpleXMLElement $simpleXmlElement
     *
     * @return array<\SimpleXMLElement>
     */
    protected function getSimpleXmlTableElements(SimpleXMLElement $simpleXmlElement)
    {
        if ($this->hasNamespaceInSchema($simpleXmlElement)) {
            $simpleXmlElement->registerXPathNamespace('s', 'spryker:schema-01');

            /** @phpstan-var array<\SimpleXMLElement> */
            return $simpleXmlElement->xpath('//s:table');
        }

        return $simpleXmlElement->xpath('//table');
    }
}
