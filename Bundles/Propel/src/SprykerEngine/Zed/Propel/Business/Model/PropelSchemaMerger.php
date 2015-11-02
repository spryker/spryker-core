<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Business\Model;

use SprykerEngine\Zed\Propel\Business\Exception\SchemaMergeException;
use Symfony\Component\Finder\SplFileInfo;

class PropelSchemaMerger implements PropelSchemaMergerInterface
{

    /**
     * @param SplFileInfo[] $schemaFiles
     *
     * @throws SchemaMergeException
     *
     * @return string
     */
    public function merge(array $schemaFiles)
    {
        $this->checkConsistency($schemaFiles);
        $mergeTargetXmlElement = $this->createMergeTargetXmlElement(current($schemaFiles));
        $schemaXmlElements = $this->createSchemaXmlElements($schemaFiles);

        return $this->mergeSchema($mergeTargetXmlElement, $schemaXmlElements);
    }

    /**
     * @param SplFileInfo[] $schemaFiles
     *
     * @throws SchemaMergeException
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
            $fileIdentifier = $schemaFiles[0]->getFileName();
            throw new SchemaMergeException('Ambiguous use of name, package and namespace in schema file "' . $fileIdentifier . '"');
        }
    }

    /**
     * @param SplFileInfo $schemaFile
     *
     * @return \SimpleXMLElement
     */
    private function createXmlElement(SplFileInfo $schemaFile)
    {
        $xml = new \SimpleXMLElement($schemaFile->getContents());

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
     * @param SplFileInfo $schemaFile
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
        return new \SimpleXMLElement(sprintf('<database
            name="%s"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:noNamespaceSchemaLocation="http://xsd.propelorm.org/1.6/database.xsd"
            namespace="%s"
            package="%s"
            ></database>',
            $schemaDatabase,
            $schemaNamespace,
            $schemaPackage
        ));
    }

    /**
     * @param SplFileInfo[] $schemaFiles
     *
     * @throws \ErrorException
     *
     * @return \SimpleXMLElement[]
     */
    private function createSchemaXmlElements(array $schemaFiles)
    {
        $mergeSourceXmlElements = new \ArrayObject();
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
    private function mergeSchema(\SimpleXMLElement $mergeTargetXmlElement, $schemaXmlElements)
    {
        foreach ($schemaXmlElements as $schemaXmlElement) {
            $mergeTargetXmlElement = $this->mergeSchemasRecursive($mergeTargetXmlElement, $schemaXmlElement);
        }

        return $this->prettyPrint($mergeTargetXmlElement);
    }

    /**
     * @param \SimpleXmlElement $xml
     *
     * @return string
     */
    private function prettyPrint(\SimpleXmlElement $xml)
    {
        $dom = new \DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        $callback = function ($a) {
            return str_repeat(' ', intval(strlen($a[1]) / 2) * 4) . '<';
        };

        return preg_replace_callback('/^( +)</m', $callback, $dom->saveXML());
    }

    /**
     * @param \SimpleXMLElement $toXmlElement
     * @param \SimpleXMLElement $fromXmlElement
     *
     * @return \SimpleXMLElement
     */
    private function mergeSchemasRecursive(\SimpleXMLElement $toXmlElement, \SimpleXMLElement $fromXmlElement)
    {
        $toXmlElements = $this->retrieveToXmlElements($toXmlElement);

        foreach ($fromXmlElement->children() as $fromXmlChildTagName => $fromXmlChildElement) {
            $fromXmlElementName = $this->getElementName($fromXmlChildElement, $fromXmlChildTagName);
            if (true === array_key_exists($fromXmlElementName, $toXmlElements)) {
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
    private function retrieveToXmlElements(\SimpleXMLElement $toXmlElement)
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
     * @param \SimpleXMLElement $fromXmlChildElement
     * @param string $tagName
     *
     * @return string
     */
    private function getElementName(\SimpleXMLElement $fromXmlChildElement, $tagName)
    {
        $elementName = (array) $fromXmlChildElement->attributes();
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
     *
     * @throws SchemaMergeException
     *
     * @return \SimpleXMLElement
     */
    private function mergeAttributes(\SimpleXMLElement $toXmlElement, \SimpleXMLElement $fromXmlElement)
    {
        $toXmlAttributes = (array) $toXmlElement->attributes();
        if (count($toXmlAttributes) > 0) {
            $toXmlAttributes = current($toXmlAttributes);
            $alreadyHasAttributes = true;
        } else {
            $alreadyHasAttributes = false;
        }
        foreach ($fromXmlElement->attributes() as $key => $value) {
            if ($alreadyHasAttributes
                && array_key_exists($key, $toXmlAttributes)
                && $toXmlAttributes[$key] !== (string) $value
            ) {
                throw new SchemaMergeException('Ambiguous value for the same attribute for key "' . $key . '": "' . $toXmlAttributes[$key] . '" !== "' . $value . '"');
            }

            if (!$alreadyHasAttributes || !array_key_exists($key, $toXmlAttributes)) {
                $value = (string) $value;
                $toXmlElement->addAttribute($key, $value);
            }
        }

        return $toXmlElement;
    }

}
