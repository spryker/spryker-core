<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Plugin\Propel;

use SimpleXMLElement;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Propel\Dependency\Plugin\PropelSchemaElementFilterPluginInterface;

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
            $uniqueIndexElements = $this->getUniqueIndexElements($tableXmlElement);
            if (!$uniqueIndexElements) {
                continue;
            }

            $fkFieldNames = $this->getFkFieldNames($uniqueIndexElements);
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
    protected function getUniqueIndexElements(SimpleXMLElement $tableXmlElement): array
    {
        $uniqueIndexElements = [];
        foreach ($tableXmlElement->children() as $tagName => $element) {
            if ($tagName === 'unique') {
                $uniqueIndexElements[] = $element;
            }
        }

        return $uniqueIndexElements;
    }

    /**
     * @param \SimpleXMLElement[] $uniqueIndexElements
     *
     * @return string[]
     */
    protected function getFkFieldNames(array $uniqueIndexElements): array
    {
        $fkFieldNames = [];
        foreach ($uniqueIndexElements as $uniqueIndexElement) {
            $fkFieldName = $this->findFirstFkFieldNamesFromUniqueIndex($uniqueIndexElement);
            if ($fkFieldName) {
                $fkFieldNames[] = $fkFieldName;
            }
        }

        return $fkFieldNames;
    }

    /**
     * @param \SimpleXMLElement $uniqueIndexElement
     *
     * @return string|null
     */
    protected function findFirstFkFieldNamesFromUniqueIndex(SimpleXMLElement $uniqueIndexElement): ?string
    {
        foreach ($uniqueIndexElement->children() as $uniqueIndexField) {
            $fieldName = (string)$uniqueIndexField->attributes()['name'];
            if ($this->isFkField($fieldName)) {
                return $fieldName;
            }

            return null;
        }

        return null;
    }

    /**
     * @param string $fieldName
     *
     * @return bool
     */
    protected function isFkField(string $fieldName): bool
    {
        return strpos($fieldName, 'fk_') === 0;
    }

    /**
     * @param \SimpleXMLElement $tableXmlElement
     * @param string[] $fkFieldNames
     *
     * @return \SimpleXMLElement
     */
    protected function removeFkIndexes(SimpleXMLElement $tableXmlElement, array $fkFieldNames): SimpleXMLElement
    {
        $elementsToRemove = [];
        foreach ($tableXmlElement->children() as $tagName => $childXmlElement) {
            $nameAttribute = (string)$childXmlElement->attributes()['name'];
            if (
                $this->isFkIndex($tagName, $nameAttribute)
                && $this->isFkIndexInFkFieldNameList($nameAttribute, $fkFieldNames)
            ) {
                $elementsToRemove[] = $childXmlElement;
            }
        }

        $this->removeElements($elementsToRemove);

        return $tableXmlElement;
    }

    /**
     * @param string $tagName
     * @param string $nameAttribute
     *
     * @return bool
     */
    protected function isFkIndex(string $tagName, string $nameAttribute): bool
    {
        return $tagName === 'index' && strpos($nameAttribute, 'fk_') !== false;
    }

    /**
     * @param string $nameAttribute
     * @param string[] $fkFieldNames
     *
     * @return bool
     */
    protected function isFkIndexInFkFieldNameList(string $nameAttribute, array $fkFieldNames): bool
    {
        $fieldName = mb_substr($nameAttribute, strpos($nameAttribute, 'fk_'));

        return in_array($fieldName, $fkFieldNames, true);
    }

    /**
     * @param \SimpleXMLElement[] $elementsToRemove
     *
     * @return void
     */
    protected function removeElements(array $elementsToRemove): void
    {
        foreach ($elementsToRemove as $element) {
            $childNode = dom_import_simplexml($element);

            $childNode->parentNode->removeChild($childNode);
        }
    }
}
