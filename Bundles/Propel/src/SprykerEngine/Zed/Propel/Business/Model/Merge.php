<?php

namespace SprykerEngine\Zed\Propel\Business\Model;

use ArrayObject;
use SprykerEngine\Zed\Propel\Business\Exception\SchemaMergeException;
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

        $schemaPathsGroupedByFileId = $this->findAllSchemasGroupedByFileId($schemaDirectories);

        foreach ($schemaPathsGroupedByFileId as $fileId => $existingSchemaPaths) {

            $pathForGeneratedFile = $this->defineNewFilePath($this->schemaPath, $fileId);

            if ($this->needsMerge($existingSchemaPaths)) {
                $this->mergeSchemasOfOneSchemaFile($existingSchemaPaths, $fileId, $pathForGeneratedFile);
            } else {
                $this->copySchema($existingSchemaPaths, $pathForGeneratedFile);
            }
        }
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

    protected function findAllSchemasGroupedByFileId($schemaDirectories)
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
     * @param SplFileInfo $schemaData
     * @param ArrayObject $schemaArray
     * @return ArrayObject
     */
    protected function addSchemaToList(SplFileInfo $schemaData, ArrayObject $schemaArray)
    {
        $fileId = $schemaData->getRelativePathName();
        if (false === isset($schemaArray[$fileId])) {
            $schemaArray[$fileId] = [];
        }
        $schemaArray[$fileId][] = $schemaData->getPathName();
        return $schemaArray;
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
     * @param $fileId
     * @param $pathForGeneratedFile
     */
    protected function mergeSchemasOfOneSchemaFile(array $schemaPaths, $fileId, $pathForGeneratedFile)
    {
        $this->checkConsistency($schemaPaths, $fileId);

        $mergeTargetXmlElement = $this->createMergeTargetXmlElement(current($schemaPaths));

        $schemaXmlElements = $this->createSchemaXmlElements($schemaPaths, $fileId);

        $newXml = $this->mergeSchema($mergeTargetXmlElement, $schemaXmlElements);

        $this->filesystem->dumpFile($pathForGeneratedFile, $newXml);
    }

    /**
     * @param $schemaPaths
     * @param $fileId
     * @throws SchemaMergeException
     */
    protected function checkConsistency($schemaPaths, $fileId)
    {
        $childArray = [];
        foreach ($schemaPaths as $schemaPath) {

            $schemaAttributes = $this->createXmlElement($schemaPath)->attributes();
            $schemaKey = $this->createKey($schemaAttributes['name'], $schemaAttributes['package'], $schemaAttributes['namespace']);
            $childArray[$schemaKey] = true;
        }

        if (count($childArray) !== 1) {
            throw new SchemaMergeException('Ambiguous use of name, package and namespace in schema file "' . $fileId . '"');
        }

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
     * @param $schemaPath
     * @return \SimpleXMLElement
     */
    protected function createMergeTargetXmlElement($schemaPath)
    {
        $schemaAttributes = $this->createXmlElement($schemaPath)->attributes();
        return $this->createNewXml($schemaAttributes['name'], $schemaAttributes['namespace'], $schemaAttributes['package']);
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
     * @param $schemaPaths
     * @param $fileName
     * @return \SimpleXMLElement[]
     * @throws \ErrorException
     */
    protected function createSchemaXmlElements($schemaPaths)
    {
        $mergeSourceXmlElements = new \ArrayObject();
        foreach ($schemaPaths as $schemaPath) {
            $mergeSourceXmlElements[] = $this->createXmlElement($schemaPath);
        }
        return $mergeSourceXmlElements;
    }

    /**
     * @param $mergeTargetXmlElement
     * @param $schemaXmlElements
     * @return string $xml
     */
    protected function mergeSchema($mergeTargetXmlElement, $schemaXmlElements)
    {
        foreach ($schemaXmlElements as $schemaXmlElement) {
            $mergeTargetXmlElement = $this->mergeSchemasRecursive($mergeTargetXmlElement, $schemaXmlElement);
        }
        return $mergeTargetXmlElement->asXML();
    }

    /**
     * @param \SimpleXMLElement $toXmlElement
     * @param \SimpleXMLElement $fromXmlElement
     * @return \SimpleXMLElement
     */
    protected function mergeSchemasRecursive(\SimpleXMLElement $toXmlElement, \SimpleXMLElement $fromXmlElement)
    {
        $toXmlElements = $this->retrieveToXmlElements($toXmlElement);

        foreach ($fromXmlElement->children() as $fromXmlChildTagName => $fromXmlChildElement) {
            $fromXmlElementName = $this->getElementName($fromXmlChildElement, $fromXmlChildTagName);

            if (true === array_key_exists($fromXmlElementName, $toXmlElements)) {
                // merge element
                $toXmlElementChild = $toXmlElements[$fromXmlElementName];
            } else {
                // add element
                $toXmlElementChild = $toXmlElement->addChild($fromXmlChildTagName, $fromXmlChildElement);
            }
            $this->mergeAttributes($toXmlElementChild, $fromXmlChildElement);

            // Recursive call:
            $this->mergeSchemasRecursive($toXmlElementChild, $fromXmlChildElement);

        }
        return $toXmlElement;
    }

    /**
     * @param \SimpleXMLElement $toXmlElement
     * @return ArrayObject
     */
    protected function retrieveToXmlElements(\SimpleXMLElement $toXmlElement)
    {
        $toXmlElementNames = new \ArrayObject();
        $toXmlElementChildren = $toXmlElement->children();
        foreach ($toXmlElementChildren as $toXmlChildTagName => $toXmlChildElement) {
            $toXmlElementName = $this->getElementName($toXmlChildElement, $toXmlChildTagName);
            $toXmlElementNames[$toXmlElementName] = $toXmlChildElement;
        }
        return $toXmlElementNames;
    }

    /**
     * @param $fromXmlChildElement
     * @param $tagName
     * @return array|mixed|string
     */
    protected function getElementName($fromXmlChildElement, $tagName)
    {
        $elementName = (array)$fromXmlChildElement->attributes();
        $elementName = current($elementName);
        if (is_array($elementName) && array_key_exists('name', $elementName)) {
            $elementName = $tagName . '|' . $elementName['name'];
        }

        if (empty($elementName) || is_array($elementName)) {
            $elementName = uniqid('anonymous_');
        }

        return $elementName;
    }

    /**
     * @param \SimpleXMLElement $toXmlElement
     * @param \SimpleXMLElement $fromXmlElement
     * @return \SimpleXMLElement
     * @throws SchemaMergeException
     */
    protected function mergeAttributes(\SimpleXMLElement $toXmlElement, \SimpleXMLElement $fromXmlElement)
    {
        $toXmlAttributes = (array)$toXmlElement->attributes();
        if (count($toXmlAttributes) > 0) {
            $toXmlAttributes = current($toXmlAttributes);
            $alreadyHasAttributes = true;
        } else {
            $alreadyHasAttributes = false;
        }
        foreach ($fromXmlElement->attributes() as $key => $value) {

            if (true === $alreadyHasAttributes
                && true === array_key_exists($key, $toXmlAttributes)
                && $toXmlAttributes[$key] != $value
            ) {
                throw new SchemaMergeException('Ambiguous value for the same attribute for key: ' . $key . ': "' . $toXmlAttributes[$key] . '" !== "' . $value . '"');
            }

            if (false === $alreadyHasAttributes || false === array_key_exists($key, $toXmlAttributes)) {
                $value = (string)$value;
                $toXmlElement->addAttribute($key, $value);
            }
        }
        return $toXmlElement;
    }

    /**
     * @param array $schemaPaths
     * @param $pathForGeneratedFile
     */
    protected function copySchema(array $schemaPaths, $pathForGeneratedFile)
    {
        $oldFilePath = current($schemaPaths);


        $this->filesystem->copy($oldFilePath, $pathForGeneratedFile);
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
}
