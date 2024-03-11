<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\DynamicEntity;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConditionsTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldConditionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldValidationTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationRelation;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationRelationFieldMapping;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationRelationQuery;
use Spryker\Zed\DynamicEntity\Business\DynamicEntityFacade;
use Spryker\Zed\DynamicEntity\Business\DynamicEntityFacadeInterface;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(\SprykerTest\Zed\DynamicEntity\PHPMD)
 */
class DynamicEntityBusinessTester extends Actor
{
    use _generated\DynamicEntityBusinessTesterActions;

    /**
     * @var string
     */
    public const BAR_TABLE_ALIAS = 'BAR';

    /**
     * @var string
     */
    public const IN_FILTER_CONDITION = '{"in": ["BAR", "FOO"]}';

    /**
     * @var string
     */
    public const TABLE_ALIAS_FIELD_NAME = 'table_alias';

    /**
     * @var string
     */
    public const FOO_TABLE_ALIAS_1 = 'FOO';

    /**
     * @var string
     */
    public const TABLE_NAME = 'spy_dynamic_entity_configuration';

    /**
     * @var string
     */
    public const FOO_DEFINITION = '{"identifier":"id_dynamic_entity_configuration","fields":[{"fieldName":"id_dynamic_entity_configuration","fieldVisibleName":"id_dynamic_entity_configuration","isEditable":true,"isCreatable":false,"type":"integer","validation":{"isRequired":false}},{"fieldName":"table_alias","fieldVisibleName":"table_alias","type":"string","isEditable":true,"isCreatable":true,"validation":{"isRequired":false}},{"fieldName":"table_name","fieldVisibleName":"table_name","type":"string","isEditable":true,"isCreatable":true,"validation":{"isRequired":false}},{"fieldName":"is_active","fieldVisibleName":"is_active","isEditable":false,"isCreatable":true,"type":"boolean","validation":{"isRequired":false}},{"fieldName":"definition","fieldVisibleName":"definition","type":"string","isEditable":true,"isCreatable":true,"validation":{"isRequired":false}}]}';

    /**
     * @var string
     */
    public const TEST_TABLE_ALIAS = 'test_table_alias';

    /**
     * @var array<string>
     */
    public const RELATION_TEST_CHAINS = [
        'productAbstractProducts.productSearch',
        'productAbstractProducts.productStocks',
        'productAbstractProducts.productLocalizedAttributes.thisIsTest',
    ];

    /**
     * @param string|null $tableName
     * @param string|null $tableAlias
     * @param int|null $id
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer
     */
    public function createDynamicEntityConfigurationTransfer(
        ?string $tableName = null,
        ?string $tableAlias = null,
        ?int $id = null
    ): DynamicEntityConfigurationTransfer {
        $idDynamicEntityFieldDefinitionTransfer = (new DynamicEntityFieldDefinitionTransfer())
            ->setFieldName('id_test_table')
            ->setFieldVisibleName('id')
            ->setType('integer')
            ->setIsCreatable(false)
            ->setIsEditable(false)
            ->setValidation((new DynamicEntityFieldValidationTransfer())
                ->setIsRequired(false));

        $stringDynamicEntityFieldDefinitionTransfer = (new DynamicEntityFieldDefinitionTransfer())
            ->setFieldName('string_field')
            ->setFieldVisibleName('string_field')
            ->setType('string')
            ->setIsCreatable(true)
            ->setIsEditable(true)
            ->setValidation(
                (new DynamicEntityFieldValidationTransfer())
                    ->setIsRequired(true)
                    ->setMinLength(1)
                    ->setMaxLength(255),
            );

        $intDynamicEntityFieldDefinitionTransfer = (new DynamicEntityFieldDefinitionTransfer())
            ->setFieldName('int_field')
            ->setFieldVisibleName('int_field')
            ->setType('integer')
            ->setIsCreatable(true)
            ->setIsEditable(true)
            ->setValidation(
                (new DynamicEntityFieldValidationTransfer())
                    ->setIsRequired(true)
                    ->setMin(100)
                    ->setMax(255),
            );

        $boolDynamicEntityFieldDefinitionTransfer = (new DynamicEntityFieldDefinitionTransfer())
            ->setFieldName('bool_field')
            ->setFieldVisibleName('bool_field')
            ->setType('boolean')
            ->setIsCreatable(true)
            ->setIsEditable(true)
            ->setValidation(
                (new DynamicEntityFieldValidationTransfer())
                    ->setIsRequired(true),
            );

        $decimalDynamicEntityFieldDefinitionTransfer = (new DynamicEntityFieldDefinitionTransfer())
            ->setFieldName('decimal_field')
            ->setFieldVisibleName('decimal_field')
            ->setType('decimal')
            ->setIsCreatable(true)
            ->setIsEditable(true)
            ->setValidation(
                (new DynamicEntityFieldValidationTransfer())
                    ->setIsRequired(true)
                    ->setScale(2)
                    ->setPrecision(10),
            );

        $dynamicEntityDefinitionTransfer = (new DynamicEntityDefinitionTransfer())
            ->setIdentifier('id_test_table')
            ->addFieldDefinition($idDynamicEntityFieldDefinitionTransfer)
            ->addFieldDefinition($stringDynamicEntityFieldDefinitionTransfer)
            ->addFieldDefinition($intDynamicEntityFieldDefinitionTransfer)
            ->addFieldDefinition($boolDynamicEntityFieldDefinitionTransfer)
            ->addFieldDefinition($decimalDynamicEntityFieldDefinitionTransfer);

        $dynamicEntityConfigurationTransfer = (new DynamicEntityConfigurationTransfer())
            ->setIsActive(true)
            ->setTableAlias($tableAlias ?? 'test-table')
            ->setTableName($tableName ?? 'spy_test_table')
            ->setDynamicEntityDefinition($dynamicEntityDefinitionTransfer);

        if ($id) {
            $dynamicEntityConfigurationTransfer->setIdDynamicEntityConfiguration($id);
        }

        return $dynamicEntityConfigurationTransfer;
    }

    /**
     * @param string|null $tableName
     * @param string|null $tableAlias
     * @param int|null $id
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer
     */
    public function createDynamicEntityConfigurationCollectionRequestTransfer(
        ?string $tableName = null,
        ?string $tableAlias = null,
        ?int $id = null
    ): DynamicEntityConfigurationCollectionRequestTransfer {
        $dynamicEntityConfigurationCollectionRequestTransfer = new DynamicEntityConfigurationCollectionRequestTransfer();

        $dynamicEntityConfigurationTransfer = $this->createDynamicEntityConfigurationTransfer($tableName, $tableAlias, $id);

        $dynamicEntityConfigurationCollectionRequestTransfer->addDynamicEntityConfiguration($dynamicEntityConfigurationTransfer);

        return $dynamicEntityConfigurationCollectionRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer
     */
    public function createDynamicEntityConfigurationCollectionRequestTransferByDynamicEntityFieldDefinitionTransfer(
        DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
    ): DynamicEntityConfigurationCollectionRequestTransfer {
        $dynamicEntityDefinitionTransfer = (new DynamicEntityDefinitionTransfer())
            ->setIdentifier('id')
            ->addFieldDefinition($dynamicEntityFieldDefinitionTransfer);

        $dynamicEntityConfigurationTransfer = (new DynamicEntityConfigurationTransfer())
            ->setIsActive(true)
            ->setTableAlias('xxxx')
            ->setTableName('spy_xxxx')
            ->setDynamicEntityDefinition($dynamicEntityDefinitionTransfer);

        $dynamicEntityConfigurationCollectionRequestTransfer = new DynamicEntityConfigurationCollectionRequestTransfer();

        $dynamicEntityConfigurationCollectionRequestTransfer->addDynamicEntityConfiguration($dynamicEntityConfigurationTransfer);

        return $dynamicEntityConfigurationCollectionRequestTransfer;
    }

    /**
     * @param int $idDynamicEntityConfiguration
     *
     * @return \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration|null
     */
    public function findDynamicEntityConfigurationById(int $idDynamicEntityConfiguration): ?SpyDynamicEntityConfiguration
    {
        return $this->createSpyDynamicEntityConfigurationQuery()
            ->filterByIdDynamicEntityConfiguration($idDynamicEntityConfiguration)
            ->findOne();
    }

    /**
     * @param string $tableAlias
     *
     * @return \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration|null
     */
    public function findDynamicEntityConfiguration(string $tableAlias): ?SpyDynamicEntityConfiguration
    {
        return $this->createSpyDynamicEntityConfigurationQuery()
            ->filterByTableAlias($tableAlias)
            ->findOne();
    }

    /**
     * @param string $tableName
     * @param string $tableAlias
     *
     * @return int
     */
    public function createEntity(string $tableName, string $tableAlias): int
    {
        $spyDynamicEntityConfiguration = (new SpyDynamicEntityConfiguration())
            ->setIsActive(true)
            ->setTableAlias($tableAlias)
            ->setTableName($tableName)
            ->setDefinition(json_encode($this->createDefinitionArray()));

        $spyDynamicEntityConfiguration->save();

        return $spyDynamicEntityConfiguration->getIdDynamicEntityConfiguration();
    }

    /**
     * @return array<string, mixed>
     */
    public function createDefinitionArray(): array
    {
        return [
            'identifier' => 'id_test_table',
            'fields' => [
                [
                    'field_name' => 'id_test_table',
                    'field_visible_name' => 'id',
                    'type' => 'integer',
                    'is_creatable' => false,
                    'is_editable' => false,
                    'validation' => [
                        'is_required' => false,
                    ],
                ],
                [
                    'field_name' => 'string_field',
                    'field_visible_name' => 'string_field',
                    'type' => 'string',
                    'is_creatable' => true,
                    'is_editable' => true,
                    'validation' => [
                        'is_required' => true,
                        'min_length' => 1,
                        'max_length' => 255,
                    ],
                ],
                [
                    'field_name' => 'int_field',
                    'field_visible_name' => 'int_field',
                    'type' => 'integer',
                    'is_creatable' => true,
                    'is_editable' => true,
                    'validation' => [
                        'is_required' => true,
                        'min' => 100,
                        'max' => 255,
                    ],
                ],
                [
                    'field_name' => 'bool_field',
                    'field_visible_name' => 'bool_field',
                    'type' => 'boolean',
                    'is_creatable' => true,
                    'is_editable' => true,
                    'validation' => [
                        'is_required' => true,
                    ],
                ],
                [
                    'field_name' => 'decimal_field',
                    'field_visible_name' => 'decimal_field',
                    'type' => 'decimal',
                    'is_creatable' => true,
                    'is_editable' => true,
                    'validation' => [
                        'is_required' => true,
                        'scale' => 2,
                        'precision' => 10,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function getExpectedDefinition(): string
    {
        return <<<'EOT'
{"identifier":"id_test_table","fields":[{"fieldName":"id_test_table","fieldVisibleName":"id","type":"integer","isCreatable":false,"isEditable":false,"validation":{"isRequired":false}},{"fieldName":"string_field","fieldVisibleName":"string_field","type":"string","isCreatable":true,"isEditable":true,"validation":{"isRequired":true,"minLength":1,"maxLength":255}},{"fieldName":"int_field","fieldVisibleName":"int_field","type":"integer","isCreatable":true,"isEditable":true,"validation":{"isRequired":true,"min":100,"max":255}},{"fieldName":"bool_field","fieldVisibleName":"bool_field","type":"boolean","isCreatable":true,"isEditable":true,"validation":{"isRequired":true}},{"fieldName":"decimal_field","fieldVisibleName":"decimal_field","type":"decimal","isCreatable":true,"isEditable":true,"validation":{"isRequired":true,"scale":2,"precision":10}}]}
EOT;
    }

    /**
     * @return string
     */
    public function getDefinitionForDynamicEntityConfigurationRelation(): string
    {
        return <<<DEFINITION
{
  "identifier": "id_dynamic_entity_configuration_relation",
  "fields": [
    {
      "fieldName": "id_dynamic_entity_configuration_relation",
      "fieldVisibleName": "id_dynamic_entity_configuration_relation",
      "isEditable": false,
      "isCreatable": false,
      "type": "integer",
      "validation": {
        "isRequired": false
      }
    },
    {
      "fieldName": "fk_parent_dynamic_entity_configuration",
      "fieldVisibleName": "fk_parent_dynamic_entity_configuration",
      "type": "integer",
      "isEditable": true,
      "isCreatable": true,
      "validation": {
        "isRequired": true
      }
    },
    {
      "fieldName": "fk_child_dynamic_entity_configuration",
      "fieldVisibleName": "fk_child_dynamic_entity_configuration",
      "type": "integer",
      "isEditable": true,
      "isCreatable": true,
      "validation": {
        "isRequired": true
      }
    },
    {
      "fieldName": "name",
      "fieldVisibleName": "name",
      "type": "string",
      "isEditable": true,
      "isCreatable": true,
      "validation": {
        "isRequired": true
      }
    },
    {
      "fieldName": "is_editable",
      "fieldVisibleName": "is_editable",
      "isEditable": true,
      "isCreatable": true,
      "type": "boolean",
      "validation": {
        "isRequired": true
      }
    }
  ]
}
DEFINITION;
    }

    /**
     * @return string
     */
    public function getDefinitionForDynamicEntityConfigurationRelationFieldMapping(): string
    {
        return <<<DEFINITION
{
  "identifier": "id_dynamic_entity_configuration_relation_field_mapping",
  "fields": [
    {
      "fieldName": "id_dynamic_entity_configuration_relation_field_mapping",
      "fieldVisibleName": "id_dynamic_entity_configuration_relation_field_mapping",
      "isEditable": false,
      "isCreatable": false,
      "type": "integer",
      "validation": {
        "isRequired": false
      }
    },
    {
      "fieldName": "fk_dynamic_entity_configuration_relation",
      "fieldVisibleName": "fk_dynamic_entity_configuration_relation",
      "type": "integer",
      "isEditable": true,
      "isCreatable": true,
      "validation": {
        "isRequired": true
      }
    },
    {
      "fieldName": "child_field_name",
      "fieldVisibleName": "child_field_name",
      "type": "string",
      "isEditable": true,
      "isCreatable": true,
      "validation": {
        "isRequired": true
      }
    },
    {
      "fieldName": "parent_field_name",
      "fieldVisibleName": "parent_field_name",
      "type": "string",
      "isEditable": true,
      "isCreatable": true,
      "validation": {
        "isRequired": true
      }
    }
  ]
}
DEFINITION;
    }

    /**
     * @return \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration
     */
    public function createConfigRelationEntity(): SpyDynamicEntityConfiguration
    {
        $spyDynamicEntityConfigurationEntity = new SpyDynamicEntityConfiguration();
        $spyDynamicEntityConfigurationEntity->setIsActive(true)
            ->setTableAlias('de_configuration_relation')
            ->setTableName('spy_dynamic_entity_configuration_relation')
            ->setDefinition($this->getDefinitionForDynamicEntityConfigurationRelation())
            ->save();

        return $spyDynamicEntityConfigurationEntity;
    }

    /**
     * @return \SprykerTest\Zed\DynamicEntity\SpyDynamicEntityConfiguration
     */
    public function createConfigRelationFieldMappingEntity(): SpyDynamicEntityConfiguration
    {
        $spyDynamicEntityConfigurationEntity = new SpyDynamicEntityConfiguration();
        $spyDynamicEntityConfigurationEntity->setIsActive(true)
            ->setTableAlias('de_configuration_relation_field_mapping')
            ->setTableName('spy_dynamic_entity_configuration_relation_field_mapping')
            ->setDefinition($this->getDefinitionForDynamicEntityConfigurationRelationFieldMapping())
            ->save();

        return $spyDynamicEntityConfigurationEntity;
    }

    /**
     * @param string $name
     * @param string $parentFieldName
     * @param int $parentFieldId
     * @param string $childFieldName
     * @param int $childFieldId
     *
     * @return void
     */
    public function createRelationWithFieldMapping(
        string $name,
        string $parentFieldName,
        int $parentFieldId,
        string $childFieldName,
        int $childFieldId
    ): void {
        $relationEntity = new SpyDynamicEntityConfigurationRelation();
        $relationEntity->setName($name)
            ->setFkParentDynamicEntityConfiguration((int)$parentFieldId)
            ->setFkChildDynamicEntityConfiguration((int)$childFieldId)
            ->setIsEditable(true)
            ->save();

        (new SpyDynamicEntityConfigurationRelationFieldMapping())
            ->setFkDynamicEntityConfigurationRelation($relationEntity->getIdDynamicEntityConfigurationRelation())
            ->setChildFieldName($childFieldName)
            ->setParentFieldName($parentFieldName)
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer
     * @param string $fieldName
     * @param string $value
     *
     * @return \Generated\Shared\Transfer\DynamicEntityTransfer|null
     */
    public function getDynamicEntityFromCollectionByFieldNameAndValue(
        DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer,
        string $fieldName,
        string $value
    ): ?DynamicEntityTransfer {
        foreach ($dynamicEntityCollectionTransfer->getDynamicEntities() as $dynamicEntity) {
            $fieldValue = $dynamicEntity->getFields()[$fieldName] ?? null;
            if ($fieldValue === $value) {
                return $dynamicEntity;
            }
        }

        return null;
    }

    /**
     * @param string $parentTableAlias
     * @param string $relationName
     * @param string $parentConfigurationIdField
     * @param string $childConfigurationIdField
     *
     * @return \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration
     */
    public function createDynamicEntityConfigurationWithRelationAndFieldMapping(
        string $parentTableAlias,
        string $relationName,
        string $parentConfigurationIdField,
        string $childConfigurationIdField
    ): SpyDynamicEntityConfiguration {
        $dynamicEntityConfigurationRelationEntity = $this->createConfigRelationEntity();
        $dynamicEntityConfigurationEntity = $this->findDynamicEntityConfiguration($parentTableAlias);

        $this->createRelationWithFieldMapping(
            $relationName,
            $parentConfigurationIdField,
            $dynamicEntityConfigurationEntity->getIdDynamicEntityConfiguration(),
            $childConfigurationIdField,
            $dynamicEntityConfigurationRelationEntity->getIdDynamicEntityConfiguration(),
        );

        return $dynamicEntityConfigurationEntity;
    }

    /**
     * @param string $tableAlias
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer
     */
    public function createDynamicEntityCollectionRequestTransfer(string $tableAlias): DynamicEntityCollectionRequestTransfer
    {
        $dynamicEntityCollectionRequestTransfer = new DynamicEntityCollectionRequestTransfer();
        $dynamicEntityCollectionRequestTransfer->setTableAlias($tableAlias);

        return $dynamicEntityCollectionRequestTransfer;
    }

    /**
     * @param string $tableAlias
     * @param string|null $filterCondition
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer
     */
    public function haveDynamicEntityCriteriaTransfer(
        string $tableAlias,
        ?string $filterCondition = null
    ): DynamicEntityCriteriaTransfer {
        $dynamicEntityConditionTransfer = (new DynamicEntityConditionsTransfer())
            ->setTableAlias($tableAlias);

        if ($filterCondition) {
            $dynamicEntityConditionTransfer->addFieldCondition(
                (new DynamicEntityFieldConditionTransfer())
                    ->setName('table_alias')
                    ->setValue($filterCondition),
            );
        }

        return (new DynamicEntityCriteriaTransfer())
            ->setDynamicEntityConditions($dynamicEntityConditionTransfer);
    }

    /**
     * @return \Spryker\Zed\DynamicEntity\Business\DynamicEntityFacadeInterface
     */
    public function createDynamicEntityFacade(): DynamicEntityFacadeInterface
    {
        return new DynamicEntityFacade();
    }

    /**
     * @param string $tableName
     * @param string $tableAlias
     * @param string $definition
     *
     * @return void
     */
    public function createDynamicEntityConfigurationEntity(string $tableName, string $tableAlias, string $definition): void
    {
        (new SpyDynamicEntityConfiguration())
            ->setIsActive(true)
            ->setTableName($tableName)
            ->setTableAlias($tableAlias)
            ->setDefinition($definition)
            ->setDefinition($definition)
            ->save();
    }

    /**
     * @param string $relationName
     *
     * @return \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationRelation
     */
    public function getDynamicEntityConfigurationRelationEntityByRelation(string $relationName): SpyDynamicEntityConfigurationRelation
    {
        return SpyDynamicEntityConfigurationRelationQuery::create()
            ->filterByName($relationName)
            ->find()
            ->getData()[0];
    }

    /**
     * @param string $tableAlias
     *
     * @return \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration
     */
    public function getDynamicEntityConfigurationByTableAlias(string $tableAlias): SpyDynamicEntityConfiguration
    {
        return SpyDynamicEntityConfigurationQuery::create()
            ->filterByTableAlias($tableAlias)
            ->find()
            ->getData()[0];
    }

    /**
     * @param string $tableName
     *
     * @return \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration
     */
    public function getDynamicEntityConfigurationByTableName(string $tableName): SpyDynamicEntityConfiguration
    {
        return SpyDynamicEntityConfigurationQuery::create()
            ->filterByTableName($tableName)
            ->find()
            ->getData()[0];
    }

    /**
     * @param int $idDynamicEntityConfiguration
     *
     * @return \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration
     */
    public function getDynamicEntityConfigurationByIdDynamicEntityConfiguration(int $idDynamicEntityConfiguration): SpyDynamicEntityConfiguration
    {
        return SpyDynamicEntityConfigurationQuery::create()
            ->filterByIdDynamicEntityConfiguration($idDynamicEntityConfiguration)
            ->find()
            ->getData()[0];
    }

    /**
     * @param int $idDynamicEntityConfigurationRelation
     *
     * @return \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationRelation
     */
    public function getDynamicEntityConfigurationRelationByIdDynamicEntityConfigurationRelation(
        int $idDynamicEntityConfigurationRelation
    ): SpyDynamicEntityConfigurationRelation {
        return SpyDynamicEntityConfigurationRelationQuery::create()
            ->filterByIdDynamicEntityConfigurationRelation($idDynamicEntityConfigurationRelation)
            ->find()
            ->getData()[0];
    }

    /**
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer
     */
    public function createDynamicEntityCollectionRequestTransferWithComplexData(): DynamicEntityCollectionRequestTransfer
    {
        $dynamicEntityCollectionRequestTransfer = $this
            ->createDynamicEntityCollectionRequestTransfer(static::TEST_TABLE_ALIAS);

        $dynamicEntityCollectionRequestTransfer->setDynamicEntities(new ArrayObject([
            $this->createDynamicEntityTransferWithComplexTestFields(),
        ]));

        return $dynamicEntityCollectionRequestTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\DynamicEntityTransfer
     */
    protected function createDynamicEntityTransferWithComplexTestFields(): DynamicEntityTransfer
    {
        return (new DynamicEntityTransfer())
            ->setFields([
                'productAbstractProducts' => [
                    [
                        'test_field' => 'test_value',
                        'productSearch' => [
                            [
                                'test_field' => 'test_value',
                            ],
                        ],
                        'productStocks' => [
                            [
                                'test_field' => 'test_value',
                            ],
                        ],
                        'productLocalizedAttributes' => [
                            [
                                'test_field' => 'test_value',
                                'thisIsTest' => [
                                    [
                                        'test_field' => 'test_value',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]);
    }

    /**
     * @return \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery
     */
    protected function createSpyDynamicEntityConfigurationQuery(): SpyDynamicEntityConfigurationQuery
    {
        return new SpyDynamicEntityConfigurationQuery();
    }
}
