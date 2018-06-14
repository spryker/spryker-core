<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model;

use ArrayObject;
use DOMDocument;
use SimpleXMLElement;
use Spryker\Service\UtilText\UtilTextService;
use Spryker\Zed\Propel\Business\Exception\SchemaMergeException;
use Symfony\Component\Finder\SplFileInfo;

class PropelSchemaMerger implements PropelSchemaMergerInterface
{
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
            '<database
            name="%s"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:noNamespaceSchemaLocation="http://static.spryker.com/schema-01.xsd"
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
            $mergeSourceXmlElements[] = $this->createXmlElement($schemaFile);
        }

        return $mergeSourceXmlElements;
    }

    /**
     * @param \SimpleXMLElement $mergeTargetXmlElement
     * @param \SimpleXMLElement[] $schemaXmlElements
     *
     * @return string
     */
    private function mergeSchema(SimpleXMLElement $mergeTargetXmlElement, $schemaXmlElements)
    {
        foreach ($schemaXmlElements as $schemaXmlElement) {
            $mergeTargetXmlElement = $this->mergeSchemasRecursive($mergeTargetXmlElement, $schemaXmlElement);
        }

        return $this->prettyPrint($mergeTargetXmlElement);
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

        foreach (['unique', 'foreign-key'] as $tagName) {
            foreach ($dom->getElementsByTagName($tagName) as $item) {
                $item->parentNode->appendChild($item->parentNode->removeChild($item));
            }
        }

        $callback = function ($matches) {
            $multiplier = (strlen($matches[1]) / 2) * 4;

            return str_repeat(' ', $multiplier) . '<';
        };

        return preg_replace_callback('/^( +)</m', $callback, $dom->saveXML());
    }

    /**
     * @param \SimpleXMLElement $toXmlElement
     * @param \SimpleXMLElement $fromXmlElement
     *
     * @return \SimpleXMLElement
     */
    private function mergeSchemasRecursive(SimpleXMLElement $toXmlElement, SimpleXMLElement $fromXmlElement)
    {
        $toXmlElements = $this->retrieveToXmlElements($toXmlElement);

        foreach ($fromXmlElement->children() as $fromXmlChildTagName => $fromXmlChildElement) {
            $fromXmlElementName = $this->getElementName($fromXmlChildElement, $fromXmlChildTagName);
            if (isset($toXmlElements[$fromXmlElementName])) {
                $toXmlElementChild = $toXmlElements[$fromXmlElementName];
            } else {
                $toXmlElementChild = $toXmlElement->addChild($fromXmlChildTagName, $fromXmlChildElement);
            }
            $this->mergeAttributes($toXmlElementChild, $fromXmlChildElement);
            $this->mergeSchemasRecursive($toXmlElementChild, $fromXmlChildElement);
        }

        return $toXmlElement;
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
}
