<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Plugin\Propel;

use SimpleXMLElement;
use Spryker\Shared\PropelExtension\Dependency\Plugin\PropelSchemaElementFilterPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Propel\PropelConfig getConfig()
 * @method \Spryker\Zed\Propel\Business\PropelFacadeInterface getFacade()
 * @method \Spryker\Zed\Propel\Communication\PropelCommunicationFactory getFactory()
 */
class ForeignKeyIndexPropelSchemaElementFilterPlugin extends AbstractPlugin implements PropelSchemaElementFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Filter propel schema for foreign key indexes for fields that are in use in combined indexes.
     *
     * @api
     *
     * @param \SimpleXMLElement $schemaXmlElement
     *
     * @return \SimpleXMLElement
     */
    public function filter(SimpleXMLElement $schemaXmlElement): SimpleXMLElement
    {
        foreach ($schemaXmlElement->children() as $tableXmlElement) {
            $uniqueIndexes = $this->getUniqueIndexes($tableXmlElement);
            if (!$uniqueIndexes) {
                continue;
            }

            $fkFieldNames = $this->getFkFieldNames($uniqueIndexes);
            if (!$fkFieldNames) {
                continue;
            }
            $this->removeFkIndexes($tableXmlElement, $fkFieldNames);
        }

        return $schemaXmlElement;
    }

    /**
     * @param \SimpleXMLElement $tableXmlElement
     *
     * @return \SimpleXMLElement[]
     */
    protected function getUniqueIndexes(SimpleXMLElement $tableXmlElement): array
    {
        $uniqueIndexes = [];
        foreach ($tableXmlElement->children() as $tagName => $element) {
            if ($tagName === 'unique') {
                $uniqueIndexes[] = $element;
            }
        }

        return $uniqueIndexes;
    }

    /**
     * @param \SimpleXMLElement[] $uniqueIndexes
     *
     * @return string[]
     */
    protected function getFkFieldNames(array $uniqueIndexes): array
    {
        $fkFieldNames = [];
        foreach ($uniqueIndexes as $uniqueIndex) {
            foreach ($uniqueIndex->children() as $uniqueIndexField) {
                $fieldName = (string)$uniqueIndexField->attributes()['name'];
                if (strpos($fieldName, 'fk_') === 0) {
                    $fkFieldNames[] = $fieldName;
                }
            }
        }

        return $fkFieldNames;
    }

    /**
     * @param \SimpleXMLElement $schemaXmlElement
     * @param string[] $fkFieldNames
     *
     * @return \SimpleXMLElement
     */
    protected function removeFkIndexes(SimpleXMLElement $schemaXmlElement, array $fkFieldNames): SimpleXMLElement
    {
        foreach ($schemaXmlElement->children() as $tagName => $element) {
            $elementAttributes = $element->attributes();
            if ($tagName === 'index') {
                $fieldName = mb_substr($elementAttributes['name'], strpos($elementAttributes['name'], 'fk_'));
                if (in_array($fieldName, $fkFieldNames, true)) {
                    $this->removeChild($schemaXmlElement, $tagName);
                }
            }
        }

        return $schemaXmlElement;
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
}
