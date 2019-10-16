<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model;

use ArrayObject;
use DOMDocument;
use DOMElement;
use DOMNodeList;
use SimpleXMLElement;
use Spryker\Service\UtilText\UtilTextService;
use Spryker\Zed\Propel\Business\Exception\SchemaMergeException;
use Spryker\Zed\Propel\PropelConfig;
use Symfony\Component\Finder\SplFileInfo;

class PropelSchemaMerger implements PropelSchemaMergerInterface
{
    protected const SOURCE_CORE = 'core';
    protected const SOURCE_PROJECT = 'project';

    /**
     * @var \Spryker\Zed\Propel\PropelConfig|null
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Propel\PropelConfig|null $config
     */
    public function __construct(?PropelConfig $config = null)
    {
        $this->config = $config;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo[] $schemaFiles
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\SchemaMergeException
     *
     * @return string
     */
    public function merge(array $schemaFiles)
    {
        $this->checkConsistency($schemaFiles);
        $currentSchema = current($schemaFiles);
        if (!$currentSchema) {
            throw new SchemaMergeException('Could not merge schema file. Given schema file container seems to be empty.');
        }
        $mergeTargetXmlElement = $this->createMergeTargetXmlElement($currentSchema);
        $schemaXmlElements = $this->createSchemaXmlElements($schemaFiles);

        return $this->mergeSchema($mergeTargetXmlElement, $schemaXmlElements);
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo[] $schemaFiles
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\SchemaMergeException
     *
     * @return void
     */
    private function checkConsistency(array $schemaFiles)
    {
        $childArray = [];
        foreach ($schemaFiles as $schemaFile) {
            $schemaAttributes = $this->createXmlElement($schemaFile)->attributes();
            $schemaKey = $this->createKey($schemaAttributes['name'], $schemaAttributes['package'], $schemaAttributes['namespace']);
            $childArray[$schemaKey] = true;
        }

        if (count($childArray) !== 1) {
            $fileIdentifier = $schemaFiles[0]->getFilename();
            throw new SchemaMergeException('Ambiguous use of name, package and namespace in schema file "' . $fileIdentifier . '"');
        }
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $schemaFile
     *
     * @return \SimpleXMLElement
     */
    private function createXmlElement(SplFileInfo $schemaFile)
    {
        $xml = new SimpleXMLElement($schemaFile->getContents());

        return $xml;
    }

    /**
     * @param string $schemaDatabase
     * @param string $schemaPackage
     * @param string $schemaNamespace
     *
     * @return string
     */
    private function createKey($schemaDatabase, $schemaPackage, $schemaNamespace)
    {
        $key = $schemaDatabase . '|' . $schemaPackage . '|' . $schemaNamespace;

        return $key;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $schemaFile
     *
     * @return \SimpleXMLElement
     */
    private function createMergeTargetXmlElement(SplFileInfo $schemaFile)
    {
        $schemaAttributes = $this->createXmlElement($schemaFile)->attributes();

        return $this->createNewXml($schemaAttributes['name'], $schemaAttributes['namespace'], $schemaAttributes['package']);
    }

    /**
     * @param string $schemaDatabase
     * @param string $schemaNamespace
     * @param string $schemaPackage
     *
     * @return \SimpleXMLElement
     */
    private function createNewXml($schemaDatabase, $schemaNamespace, $schemaPackage)
    {
        return new SimpleXMLElement(sprintf(
            '<database xmlns="spryker:schema-01"
            name="%s"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:schemaLocation="spryker:schema-01 https://static.spryker.com/schema-01.xsd"
            namespace="%s"
            package="%s"
            ></database>',
            $schemaDatabase,
            $schemaNamespace,
            $schemaPackage
        ));
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo[] $schemaFiles
     *
     * @return \SimpleXMLElement[]|\ArrayObject
     */
    private function createSchemaXmlElements(array $schemaFiles)
    {
        $mergeSourceXmlElements = new ArrayObject();
        foreach ($schemaFiles as $schemaFile) {
            $simpleXmlElement = $this->createXmlElement($schemaFile);
            $simpleXmlElement->addAttribute('source', $this->getSourceFromFilePath($schemaFile));
            $mergeSourceXmlElements[] = $simpleXmlElement;
        }

        return $mergeSourceXmlElements;
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    protected function getSourceFromFilePath(string $fileName): string
    {
        if (strpos($fileName, DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR) !== false) {
            return static::SOURCE_CORE;
        }

        return static::SOURCE_PROJECT;
    }

    /**
     * @param \SimpleXMLElement $mergeTargetXmlElement
     * @param \SimpleXMLElement[]|\ArrayObject $schemaXmlElements
     *
     * @return string
     */
    private function mergeSchema(SimpleXMLElement $mergeTargetXmlElement, $schemaXmlElements)
    {
        foreach ($schemaXmlElements as $schemaXmlElement) {
            $source = (string)$schemaXmlElement->attributes()['source'];
            $mergeTargetXmlElement = $this->mergeSchemasRecursive($mergeTargetXmlElement, $schemaXmlElement, $source);
        }

        $content = $this->prettyPrint($mergeTargetXmlElement);

        return $content;
    }

    /**
     * @param \SimpleXMLElement $xml
     *
     * @return string
     */
    private function prettyPrint(SimpleXMLElement $xml)
    {
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
        $this->ensureElementHierarchy($dom);

        $callback = function ($matches) {
            $multiplier = (strlen($matches[1]) / 2) * 4;

            return str_repeat(' ', $multiplier) . '<';
        };

        return preg_replace_callback('/^( +)</m', $callback, $dom->saveXML());
    }

    /**
     * @param \SimpleXMLElement $toXmlElement
     * @param \SimpleXMLElement $fromXmlElement
     * @param string $source
     *
     * @return \SimpleXMLElement
     */
    private function mergeSchemasRecursive(SimpleXMLElement $toXmlElement, SimpleXMLElement $fromXmlElement, string $source)
    {
        foreach ($fromXmlElement->children() as $fromXmlChildTagName => $fromXmlChildElement) {
            $toXmlElementChild = $this->getToXmlElementChild($toXmlElement, $fromXmlChildElement, $fromXmlChildTagName, $source);

            $this->mergeAttributes($toXmlElementChild, $fromXmlChildElement);
            $this->mergeSchemasRecursive($toXmlElementChild, $fromXmlChildElement, $source);
        }

        return $toXmlElement;
    }

    /**
     * If a child by the given name doesn't exists a new SimpleXmlElement will be returned.
     * If a child by the given name exists this SimpleXmlElement child will be returned.
     * If the child name is `index` and it does already exists (comes from core) then we replace the core SimpleXmlElement with the one from the project.
     *
     * @param \SimpleXMLElement $toXmlElement
     * @param \SimpleXMLElement $fromXmlChildElement
     * @param string $fromXmlChildTagName
     * @param string $source
     *
     * @return \SimpleXMLElement
     */
    protected function getToXmlElementChild(SimpleXMLElement $toXmlElement, SimpleXMLElement $fromXmlChildElement, string $fromXmlChildTagName, string $source): SimpleXMLElement
    {
        $toXmlElements = $this->retrieveToXmlElements($toXmlElement);
        $fromXmlElementName = $this->getElementName($fromXmlChildElement, $fromXmlChildTagName);

        if ($this->allowIndexOverriding($toXmlElement, $fromXmlChildTagName, $source) && $this->haveSameAttribute($toXmlElement->$fromXmlChildTagName, $fromXmlChildElement, 'name')) {
            $this->removeChild($toXmlElement, $fromXmlChildTagName);

            return $toXmlElement->addChild($fromXmlChildTagName, $fromXmlChildElement);
        }

        if (isset($toXmlElements[$fromXmlElementName])) {
            return $toXmlElements[$fromXmlElementName];
        }

        return $toXmlElement->addChild($fromXmlChildTagName, $fromXmlChildElement);
    }

    /**
     * Returns true if projects enabled overriding of index definitions which come from core.
     *
     * @param \SimpleXMLElement $toXmlElement
     * @param string $childName
     * @param string $source
     *
     * @return bool
     */
    protected function allowIndexOverriding(SimpleXMLElement $toXmlElement, string $childName, string $source): bool
    {
        return $this->hasConfig() && $this->config->allowIndexOverriding() && $childName === 'index' && isset($toXmlElement->$childName) && $source === static::SOURCE_PROJECT;
    }

    /**
     * @param \SimpleXMLElement $firstXmlElement
     * @param \SimpleXMLElement $secondXmlElement
     * @param string $attributeName
     *
     * @return bool
     */
    protected function haveSameAttribute(SimpleXMLElement $firstXmlElement, SimpleXMLElement $secondXmlElement, string $attributeName): bool
    {
        if (!isset($firstXmlElement->attributes()[$attributeName]) || !isset($secondXmlElement->attributes()[$attributeName])) {
            return false;
        }

        if ((string)$firstXmlElement->attributes()[$attributeName] === (string)$secondXmlElement->attributes()[$attributeName]) {
            return true;
        }

        return false;
    }

    /**
     * @param \SimpleXMLElement $toXmlElement
     *
     * @return \ArrayObject
     */
    private function retrieveToXmlElements(SimpleXMLElement $toXmlElement)
    {
        $toXmlElementNames = new ArrayObject();
        $toXmlElementChildren = $toXmlElement->children();

        foreach ($toXmlElementChildren as $toXmlChildTagName => $toXmlChildElement) {
            $toXmlElementName = $this->getElementName($toXmlChildElement, $toXmlChildTagName);
            $toXmlElementNames[$toXmlElementName] = $toXmlChildElement;
        }

        return $toXmlElementNames;
    }

    /**
     * @param \SimpleXMLElement $fromXmlChildElement
     * @param string $tagName
     *
     * @return string
     */
    private function getElementName(SimpleXMLElement $fromXmlChildElement, $tagName)
    {
        $elementName = (array)$fromXmlChildElement->attributes();
        $elementName = current($elementName);
        if (is_array($elementName) && isset($elementName['name'])) {
            $elementName = $tagName . '|' . $elementName['name'];
        }

        if (empty($elementName) || is_array($elementName)) {
            $utilTextService = new UtilTextService();
            $elementName = 'anonymous_' . $utilTextService->generateRandomString(32);
        }

        return $elementName;
    }

    /**
     * @param \SimpleXMLElement $simpleXMLElement
     * @param string $childName
     *
     * @return void
     */
    protected function removeChild(SimpleXMLElement $simpleXMLElement, string $childName): void
    {
        $childNode = dom_import_simplexml($simpleXMLElement->$childName);
        $dom = dom_import_simplexml($simpleXMLElement);
        $dom->removeChild($childNode);
    }

    /**
     * @param \SimpleXMLElement $toXmlElement
     * @param \SimpleXMLElement $fromXmlElement
     *
     * @return \SimpleXMLElement
     */
    private function mergeAttributes(SimpleXMLElement $toXmlElement, SimpleXMLElement $fromXmlElement)
    {
        $toXmlAttributes = iterator_to_array($toXmlElement->attributes());

        foreach ($fromXmlElement->attributes() as $key => $value) {
            if (isset($toXmlAttributes[$key])) {
                $toXmlElement->attributes()->$key = $value;

                continue;
            }

            $toXmlElement->addAttribute($key, $value);
        }

        return $toXmlElement;
    }

    /**
     * @param \DOMDocument $dom
     *
     * @return void
     */
    protected function ensureElementHierarchy(DOMDocument $dom): void
    {
        foreach ($dom->getElementsByTagName('table') as $tableDomElement) {
            $this->ensureTableElementHierarchy($tableDomElement);
        }
    }

    /**
     * @param \DOMElement $tableDomElement
     *
     * @return void
     */
    protected function ensureTableElementHierarchy(DOMElement $tableDomElement): void
    {
        $elementHierarchy = ['unique', 'foreign-key'];

        if ($this->hasConfig()) {
            $elementHierarchy = $this->config->getTableElementHierarchy();
        }

        $nodesToOrder = $this->getNodesToOrder($tableDomElement, $elementHierarchy);

        foreach ($nodesToOrder as $node) {
            $node['parent']->removeChild($node['item']);
            $node['parent']->appendChild($node['item']);
        }
    }

    /**
     * @param \DOMElement $dom
     * @param array $elementHierarchy
     *
     * @return array
     */
    protected function getNodesToOrder(DOMElement $dom, array $elementHierarchy): array
    {
        $nodesToOrder = [];
        foreach ($elementHierarchy as $tagName) {
            $items = $dom->getElementsByTagName($tagName);

            if ($tagName === 'column') {
                $items = $this->orderColumns($items);
            }

            foreach ($items as $item) {
                $nodesToOrder[] = [
                    'item' => $item,
                    'parent' => $item->parentNode,
                ];
            }
        }

        return $nodesToOrder;
    }

    /**
     * @param \DOMNodeList $nodeList
     *
     * @return array
     */
    protected function orderColumns(DOMNodeList $nodeList): array
    {
        $idColumns = [];
        $fkColumns = [];
        $otherColumns = [];

        foreach ($nodeList as $node) {
            $columnName = $node->attributes['name']->value;
            if (strpos($columnName, 'id_') === 0) {
                $idColumns[$columnName] = $node;
                continue;
            }
            if (strpos($columnName, 'fk_') === 0) {
                $fkColumns[$columnName] = $node;
                continue;
            }

            $otherColumns[$columnName] = $node;
        }

        ksort($idColumns);
        ksort($fkColumns);
        ksort($otherColumns);

        $nodes = array_merge($idColumns, $fkColumns, $otherColumns);

        return $nodes;
    }

    /**
     * @deprecated For BC reasons the PropelConfig is optional in the constructor. With the next major it will be required.
     *
     * @return bool
     */
    protected function hasConfig(): bool
    {
        return $this->config !== null;
    }
}
