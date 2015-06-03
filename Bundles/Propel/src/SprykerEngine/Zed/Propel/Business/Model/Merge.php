<?php

namespace SprykerEngine\Zed\Propel\Business\Model;

use ArrayObject;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Merge
{

    const SCHEMA_XML_PATTERN = '*_*.schema.xml';

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @var string
     */
    protected $schemaPath;

    /**
     * @param Filesystem $filesystem
     * @param Finder $finder
     * @param string $schemaPath
     */
    public function __construct(Filesystem $filesystem, Finder $finder, $schemaPath)
    {
        $this->filesystem = $filesystem;
        $this->finder = $finder;
        $this->schemaPath = $schemaPath;
    }

    public function mergeOrCopySchemaFiles()
    {
        $schemaDirectories = $this->listAllSchemaDirectories();

        $schemaFiles = $this->findAllSchemasGroupedByFileName($schemaDirectories);

        foreach ($schemaFiles as $fileName => $schemaPaths) {

            $newFilePath = $this->defineNewFilePath($this->schemaPath, $fileName);

            if ($this->needsMerge($schemaPaths)) {
                $this->mergeSchemasOfOneSchemaFile($schemaPaths, $fileName, $newFilePath);
            } else {
                $this->copySchema($schemaPaths, $newFilePath);
            }
        }
    }

    protected function findAllSchemasGroupedByFileName($schemaDirectories)
    {
        $schemaIterator = $this->finder
            ->files()
            ->in($schemaDirectories)
            ->name(self::SCHEMA_XML_PATTERN)
            ->getIterator();

        $schemaFiles = new \ArrayObject();
        foreach ($schemaIterator as $schemaData) {
            $schemaFiles = $this->addSchemaToList($schemaData, $schemaFiles);
        }

        return $schemaFiles;
    }

    /**
     * @param $generatedSchemaDir
     * @param $fileName
     * @return string
     */
    protected function defineNewFilePath($generatedSchemaDir, $fileName)
    {
        $newFilePath = $generatedSchemaDir . basename($fileName);

        return $newFilePath;
    }

    /**
     * @param $schemaPaths
     * @return bool
     */
    protected function needsMerge($schemaPaths)
    {
        return count($schemaPaths) > 1;
    }

    /**
     * @param array $schemaPaths
     * @param $fileName
     * @param $newFilePath
     */
    protected function mergeSchemasOfOneSchemaFile(array $schemaPaths, $fileName, $newFilePath)
    {
        $newSchemas = $this->createSchemaXmlElements($schemaPaths, $fileName);
        $newXml = $this->mergeSchema($newSchemas);
        $this->filesystem->dumpFile($newFilePath, $newXml);
    }

    /**
     * @param $schemaPaths
     * @return \SimpleXMLElement[]
     */
    protected function createSchemaXmlElements($schemaPaths, $fileName)
    {
        $newSchemas = new ArrayObject();
        foreach ($schemaPaths as $schemaPath) {

            $schemaXmlElement = $this->createXmlElement($schemaPath);
            $schemaAttributes = $schemaXmlElement->attributes();

            $schemaDatabase = $schemaAttributes['name'];
            $schemaPackage = $schemaAttributes['package'];
            $schemaNamespace = $schemaAttributes['namespace'];

            $childKey = $this->createKey($schemaDatabase, $schemaPackage, $schemaNamespace);

            if (!isset($newSchemas[$childKey])) {
                $newSchemas[$childKey] = [
                    'newXml' => $this->createNewXml($schemaDatabase, $schemaNamespace, $schemaPackage),
                    'existing' => []
                ];
            }
            $newSchemas[$childKey]['existing'][] = $schemaXmlElement;
        }
        $this->doConsistencyCheck($fileName, $newSchemas);
        return $newSchemas;
    }

    /**
     * @param $orginalSchemaFile
     * @return \SimpleXMLElement
     */
    protected function createXmlElement($orginalSchemaFile)
    {
        $content = file_get_contents($orginalSchemaFile);
        $xml = new \SimpleXMLElement($content);
        return $xml;
    }

    /**
     * @param $schemaDatabase
     * @param $schemaPackage
     * @param $schemaNamespace
     * @return string
     */
    protected function createKey($schemaDatabase, $schemaPackage, $schemaNamespace)
    {
        $key = $schemaDatabase . '|' . $schemaPackage . '|' . $schemaNamespace;
        return $key;
    }

    /**
     * @param $schemaDatabase
     * @param $schemaNamespace
     * @param $schemaPackage
     * @return \SimpleXMLElement
     */
    protected function createNewXml($schemaDatabase, $schemaNamespace, $schemaPackage)
    {
        return new \SimpleXMLElement('<database
          name="' . $schemaDatabase . '"
          defaultIdMethod="native"
          defaultPhpNamingMethod="underscore"
          xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
          xsi:noNamespaceSchemaLocation="http://xsd.propelorm.org/1.6/database.xsd"
          namespace="' . $schemaNamespace . '"
          package="' . $schemaPackage . '"
          ></database>');
    }

    /**
     * @param $fileId
     * @param $newSchemas
     * @throws \ErrorException
     */
    protected function doConsistencyCheck($fileId, $newSchemas)
    {
        if (count($newSchemas) !== 1) {
            // TODO own bundle exception
            throw new \ErrorException('Ambiguous use of name, package and namespace in schema file "' . $fileId . '"');
        }
    }

    /**
     * @param $schemaPaths
     * @return string
     */
    protected function mergeSchema($newSchemas)
    {
        $newXmls = [];

        foreach ($newSchemas as $schemaKey => $schemaXmls) {
            $newXml = $schemaXmls['newXml'];
            $tables = new \ArrayObject();
            foreach ($schemaXmls['existing'] as $existingXml) {
                $newXml = $this->mergeSchemasRecursiv($newXml, $existingXml, $tables);
            }
            $newXmls[$schemaKey] = $newXml->asXML();
        }
        return $newXmls;
    }

    protected function mergeSchemasRecursiv(\SimpleXMLElement $toXml, \SimpleXMLElement $fromXml, \ArrayObject $elementsWithName)
    {
        $toXmlAttributes = (array)$toXml->attributes();
        if (count($toXmlAttributes) > 0) {
            $toXmlAttributes = current($toXmlAttributes);
            $alreadyHasAttributes = true;
        } else {
            $alreadyHasAttributes = false;
        }
        foreach ($fromXml->attributes() as $key => $value) {
            if (false === $alreadyHasAttributes || false === array_key_exists($key, $toXmlAttributes)) {
                $value = (string)$value;
                $toXml->addAttribute($key, $value);
            }
        }
        $children = $fromXml->children();
        foreach ($children as $childName => $fromXmlChild) {
            /* @var $fromXmlChild \SimpleXMLElement */
            $fromName = (array)$fromXmlChild->attributes();
            $fromName = current($fromName);
            if (is_array($fromName) && array_key_exists('name', $fromName)) {
                $fromName = $childName . '|' . $fromName['name'];
                if (!isset($elementsWithName[$fromName])) {
                    $toXmlNewChild = $toXml->addChild($childName, $fromXmlChild);
                    $elementsWithName[$fromName] = $toXmlNewChild;
                } else {
                    $toXmlNewChild = $elementsWithName[$fromName];
                }
            } else {
                $toXmlNewChild = $toXml->addChild($childName, $fromXmlChild);
            }
            $this->mergeSchemasRecursiv($toXmlNewChild, $fromXmlChild, $elementsWithName);
        }
        return $toXml;
    }

    /**
     * @param array $schemaPaths
     * @param $newFilePath
     */
    protected function copySchema(array $schemaPaths, $newFilePath)
    {
        $oldFilePath = current($schemaPaths);


        $this->filesystem->copy($oldFilePath, $newFilePath);
    }

    /**
     * @return array
     */
    protected function listAllSchemaDirectories()
    {
        $dirs = [];

        // TODO Pathes should not appear here
        $directoryPatterns = [
            APPLICATION_VENDOR_DIR . '/*/*/*/*/src/*/Zed/*/Persistence/Propel/Schema/',
            APPLICATION_SOURCE_DIR . '/*/Zed/*/Persistence/Propel/Schema/',
        ];

        foreach ($directoryPatterns as $directoryPattern) {
            $dirs = array_merge($dirs, glob($directoryPattern));
        }
        return $dirs;
    }

    /**
     * @param SplFileInfo $schemaData
     * @param ArrayObject $schemaArray
     * @return ArrayObject
     */
    protected function addSchemaToList(SplFileInfo $schemaData, ArrayObject $schemaArray)
    {
        $fileName = $schemaData->getRelativePathName();
        if (false === isset($schemaArray[$fileName])) {
            $schemaArray[$fileName] = [];
        }
        $schemaArray[$fileName][] = $schemaData->getPathName();
        return $schemaArray;
    }
}
