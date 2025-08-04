<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\DynamicEntity\Business\Creator\DynamicEntityConfiguration;

use ArrayObject;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldValidationTransfer;
use Propel\Generator\Model\PropelTypes;
use SimpleXMLElement;
use Spryker\Zed\DynamicEntity\DynamicEntityConfig;
use Symfony\Component\Finder\Finder;

class DynamicEntityConfigurationColumnDetailProvider implements DynamicEntityConfigurationColumnDetailProviderInterface
{
    /**
     * @var string
     */
    protected const FIELD_DEFINITION_TYPE_STRING = 'string';

    /**
     * @var string
     */
    protected const FIELD_DEFINITION_TYPE_INTEGER = 'integer';

    /**
     * @var string
     */
    protected const FIELD_DEFINITION_TYPE_DECIMAL = 'decimal';

    /**
     * @var string
     */
    protected const FIELD_DEFINITION_TYPE_BOOLEAN = 'boolean';

    /**
     * @param \Spryker\Zed\DynamicEntity\DynamicEntityConfig $dynamicEntityConfig
     */
    public function __construct(protected DynamicEntityConfig $dynamicEntityConfig)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer
     */
    public function provideColumDetails(
        DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
    ): DynamicEntityConfigurationCollectionTransfer {
        foreach ($dynamicEntityConfigurationCollectionTransfer->getDynamicEntityConfigurations() as $dynamicEntityConfigurationTransfer) {
            $this->provideColumDetailsForConfiguration($dynamicEntityConfigurationTransfer);
        }

        return $dynamicEntityConfigurationCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer
     */
    protected function provideColumDetailsForConfiguration(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): DynamicEntityConfigurationTransfer {
        $tableName = $dynamicEntityConfigurationTransfer->getTableNameOrFail();

        $tableData = $this->findTableDataByName($tableName);

        if ($tableData) {
            return $this->addFieldDefinitions($dynamicEntityConfigurationTransfer, $tableData);
        }

        return $dynamicEntityConfigurationTransfer;
    }

    /**
     * @param string $tableName
     *
     * @return array<string, mixed>|null
     */
    protected function findTableDataByName(string $tableName): ?array
    {
        $finder = new Finder();
        $finder->files()->in($this->dynamicEntityConfig->getPropelOrmPathToMergedSchemaFiles());

        foreach ($finder as $mergedSchemaFile) {
            $mergedSchemaXml = simplexml_load_file($mergedSchemaFile->getRealPath());

            if ($mergedSchemaXml === false) {
                continue;
            }

            $xpathQuery = '//table[@name="' . $tableName . '"]';

            $namespaces = $mergedSchemaXml->getNamespaces(true);

            if (isset($namespaces[''])) {
                // Register the default namespace with a custom prefix (e.g., 'd' for 'default')
                $mergedSchemaXml->registerXPathNamespace('d', $namespaces['']);
                $xpathQuery = '//d:table[@name="' . $tableName . '"]';
            }

            foreach ((array)$mergedSchemaXml->xpath($xpathQuery) as $tableElement) {
                return [
                    'xmlDocument' => $mergedSchemaXml,
                    'tableElement' => $tableElement,
                    'tableName' => $tableName,
                    'hasNamespace' => isset($namespaces['']),
                ];
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<string, mixed> $tableData
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer
     */
    protected function addFieldDefinitions(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $tableData
    ): DynamicEntityConfigurationTransfer {
        $dynamicEntityDefinitionTransfer = $dynamicEntityConfigurationTransfer->getDynamicEntityDefinition() ?? new DynamicEntityDefinitionTransfer();

        $xmlDocument = $tableData['xmlDocument'];
        $tableName = $tableData['tableName'];
        $hasNamespace = $tableData['hasNamespace'];

        // Build XPath query for columns of this specific table
        $columnXpathQuery = '//table[@name="' . $tableName . '"]/column';

        if ($hasNamespace) {
            // Register the default namespace with a custom prefix if not already registered
            $xmlDocument->registerXPathNamespace('d', $xmlDocument->getNamespaces(true)['']);
            $columnXpathQuery = '//d:table[@name="' . $tableName . '"]/d:column';
        }

        $columnElements = $xmlDocument->xpath($columnXpathQuery);

        $dynamicEntityFieldDefinitionTransfers = new ArrayObject();

        $fieldDefinitions = $dynamicEntityDefinitionTransfer->getFieldDefinitions();

        foreach ($columnElements as $columnXmlElement) {
            $fieldDefinition = $this->findFieldDefinitionByName(
                (string)$columnXmlElement['name'],
                $fieldDefinitions,
            );
            $dynamicEntityFieldDefinitionTransfer = $this->getDynamicEntityFieldDefinitionTransfer($columnXmlElement, $fieldDefinition);
            $dynamicEntityFieldDefinitionTransfer = $this->addColumnDescriptionToFieldDefinition($columnXmlElement, $dynamicEntityFieldDefinitionTransfer);
            $dynamicEntityFieldDefinitionTransfer = $this->addEnumToFieldDefinition($columnXmlElement, $dynamicEntityFieldDefinitionTransfer);
            $dynamicEntityFieldDefinitionTransfer = $this->addExamplesToFieldDefinition($columnXmlElement, $dynamicEntityFieldDefinitionTransfer);

            $dynamicEntityFieldDefinitionTransfers->append($dynamicEntityFieldDefinitionTransfer);
        }

        $dynamicEntityDefinitionTransfer->setFieldDefinitions($dynamicEntityFieldDefinitionTransfers);
        $dynamicEntityConfigurationTransfer->setDynamicEntityDefinition($dynamicEntityDefinitionTransfer);

        return $dynamicEntityConfigurationTransfer;
    }

    /**
     * @param string $fieldName
     * @param \ArrayObject<int, \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer> $dynamicEntityFieldDefinitionTransferCollection
     *
     * @return \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer|null
     */
    protected function findFieldDefinitionByName(
        string $fieldName,
        ArrayObject $dynamicEntityFieldDefinitionTransferCollection
    ): ?DynamicEntityFieldDefinitionTransfer {
        foreach ($dynamicEntityFieldDefinitionTransferCollection as $dynamicEntityFieldDefinitionTransfer) {
            if ($dynamicEntityFieldDefinitionTransfer->getFieldName() === $fieldName) {
                return $dynamicEntityFieldDefinitionTransfer;
            }
        }

        return null;
    }

    /**
     * @param \SimpleXMLElement $columnXmlElement
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer|null $existingDynamicEntityFieldDefinitionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer
     */
    protected function getDynamicEntityFieldDefinitionTransfer(
        SimpleXMLElement $columnXmlElement,
        ?DynamicEntityFieldDefinitionTransfer $existingDynamicEntityFieldDefinitionTransfer
    ): DynamicEntityFieldDefinitionTransfer {
        $columnName = (string)$columnXmlElement['name'];

        $dynamicEntityFieldDefinitionTransfer = new DynamicEntityFieldDefinitionTransfer();
        $dynamicEntityFieldDefinitionTransfer
            ->setFieldName($columnName)
            ->setFieldVisibleName($columnName)
            ->setType($this->mapPropelTypesToFieldType($columnXmlElement))
            ->setIsCreatable(false)
            ->setIsEditable(false)
            ->setIsEnabled(true);

        if ($existingDynamicEntityFieldDefinitionTransfer) {
            $dynamicEntityFieldDefinitionTransfer->fromArray($existingDynamicEntityFieldDefinitionTransfer->toArray(), true);
        }

        $dynamicEntityFieldValidationTransfer = new DynamicEntityFieldValidationTransfer();
        $dynamicEntityFieldValidationTransfer->setIsRequired($this->getIsColumnRequired($columnXmlElement));

        $maxLength = $this->getMaxLength($columnXmlElement);

        if ($maxLength) {
            $dynamicEntityFieldValidationTransfer->setMaxLength($maxLength);
        }

        $dynamicEntityFieldDefinitionTransfer->setValidation($dynamicEntityFieldValidationTransfer);

        return $dynamicEntityFieldDefinitionTransfer;
    }

    /**
     * @param \SimpleXMLElement $columnXmlElement
     *
     * @return string
     */
    protected function mapPropelTypesToFieldType(SimpleXMLElement $columnXmlElement): string
    {
        return match ((string)$columnXmlElement['type']) {
            PropelTypes::INTEGER => static::FIELD_DEFINITION_TYPE_INTEGER,
            PropelTypes::BOOLEAN => static::FIELD_DEFINITION_TYPE_BOOLEAN,
            PropelTypes::DOUBLE, PropelTypes::DECIMAL => static::FIELD_DEFINITION_TYPE_DECIMAL,
            default => static::FIELD_DEFINITION_TYPE_STRING
        };
    }

    /**
     * @param \SimpleXMLElement $columnXmlElement
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer
     */
    protected function addColumnDescriptionToFieldDefinition(
        SimpleXMLElement $columnXmlElement,
        DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
    ): DynamicEntityFieldDefinitionTransfer {
        $columnDescription = (string)$columnXmlElement['description'];

        if (!$columnDescription) {
            $columnName = (string)$columnXmlElement['name'];

            if (str_starts_with($columnName, 'id_')) {
                $columnDescription = 'The primary key of this table.';
            }

            if (str_starts_with($columnName, 'fk_')) {
                $columnDescription = 'A foreign key to another table.';
            }
        }

        if ($columnDescription) {
            $dynamicEntityFieldDefinitionTransfer->setDescription($columnDescription);
        }

        return $dynamicEntityFieldDefinitionTransfer;
    }

    /**
     * @param \SimpleXMLElement $columnXmlElement
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer
     */
    protected function addEnumToFieldDefinition(
        SimpleXMLElement $columnXmlElement,
        DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
    ): DynamicEntityFieldDefinitionTransfer {
        if ((string)$columnXmlElement['type'] === PropelTypes::ENUM) {
            $dynamicEntityFieldDefinitionTransfer->setEnumValues($this->getValueSetData($columnXmlElement));
        }

        return $dynamicEntityFieldDefinitionTransfer;
    }

    /**
     * @param \SimpleXMLElement $columnXmlElement
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer
     */
    protected function addExamplesToFieldDefinition(
        SimpleXMLElement $columnXmlElement,
        DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
    ): DynamicEntityFieldDefinitionTransfer {
        $examples = $this->getColumnDescriptionByType($columnXmlElement, (string)$columnXmlElement['type']);

        if ($examples) {
            return $dynamicEntityFieldDefinitionTransfer;
        }

        return $dynamicEntityFieldDefinitionTransfer->setExamples($examples);
    }

    /**
     * @param \SimpleXMLElement $columnXmlElement
     * @param string $columnType
     *
     * @return string|null
     */
    protected function getColumnDescriptionByType(SimpleXMLElement $columnXmlElement, string $columnType): ?string
    {
        return match ($columnType) {
            PropelTypes::ENUM => $this->getFirstEnumValue($columnXmlElement),
            PropelTypes::TIMESTAMP => '1980-06-12 07:36:00',
            PropelTypes::BOOLEAN => '0, 1',
            default => null,
        };
    }

    /**
     * @param \SimpleXMLElement $columnXmlElement
     *
     * @return string
     */
    protected function getFirstEnumValue(SimpleXMLElement $columnXmlElement): string
    {
        $valueSet = explode(', ', $this->getValueSetData($columnXmlElement));

        return current($valueSet);
    }

    /**
     * @param \SimpleXMLElement $columnXmlElement
     *
     * @return string
     */
    protected function getValueSetData(SimpleXMLElement $columnXmlElement): string
    {
        return (string)$columnXmlElement['valueSet'];
    }

    /**
     * @param \SimpleXMLElement $columnXmlElement
     *
     * @return bool
     */
    protected function getIsColumnRequired(SimpleXMLElement $columnXmlElement): bool
    {
        if (isset($columnXmlElement['required'])) {
            return ((string)$columnXmlElement['required'] === 'true') ? true : false;
        }

        return false;
    }

    /**
     * @param \SimpleXMLElement $columnXmlElement
     *
     * @return int|null
     */
    protected function getMaxLength(SimpleXMLElement $columnXmlElement): ?int
    {
        if (isset($columnXmlElement['size'])) {
            return (int)$columnXmlElement['size'];
        }

        return null;
    }
}
