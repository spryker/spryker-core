<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\DynamicEntity;

use Codeception\Actor;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldValidationTransfer;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery;

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
        return (new SpyDynamicEntityConfigurationQuery())
            ->filterByIdDynamicEntityConfiguration($idDynamicEntityConfiguration)
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
}
