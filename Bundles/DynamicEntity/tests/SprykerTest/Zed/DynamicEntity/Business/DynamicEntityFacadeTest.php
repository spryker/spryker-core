<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DynamicEntity\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DynamicEntityConfigurationConditionsTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityRelationTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationRelationQuery;
use Spryker\Zed\DynamicEntity\Business\Installer\Validator\FieldMappingValidatorInterface;
use Spryker\Zed\DynamicEntity\DynamicEntityConfig;
use Spryker\Zed\DynamicEntity\DynamicEntityDependencyProvider;
use Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManager;
use Spryker\Zed\DynamicEntity\Persistence\DynamicEntityRepository;
use Spryker\Zed\DynamicEntityExtension\Dependency\Plugin\DynamicEntityPostCreatePluginInterface;
use Spryker\Zed\DynamicEntityExtension\Dependency\Plugin\DynamicEntityPostUpdatePluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DynamicEntity
 * @group Business
 * @group Facade
 * @group DynamicEntityFacadeTest
 * Add your own group annotations below this line
 */
class DynamicEntityFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const ERROR_INVALID_DATA_TYPE = 'dynamic_entity.validation.invalid_field_type';

    /**
     * @var string
     */
    protected const ERROR_PROVIDED_FIELD_IS_INVALID = 'dynamic_entity.validation.provided_field_is_invalid';

    /**
     * @var string
     */
    protected const ERROR_MODIFICATION_OF_IMMUTABLE_FIELD_PROHIBITED = 'dynamic_entity.validation.modification_of_immutable_field_prohibited';

    /**
     * @var string
     */
    protected const FIELD_PARENT_ENTITY_ID_CONFIGURATION = 'id_dynamic_entity_configuration';

    /**
     * @var string
     */
    protected const FIELD_CHILD_ENTITY_ID_CONFIGURATION = 'fk_parent_dynamic_entity_configuration';

    /**
     * @var string
     */
    protected const RELATION_TEST_NAME = 'relationTest';

    /**
     * @var string
     */
    protected const FOO_TABLE_ALIAS_2 = 'BAR';

    /**
     * @var string
     */
    protected const FOO_TABLE_NAME = 'spy_foo';

    /**
     * @var string
     */
    protected const BAR_TABLE_NAME = 'spy_bar';

    /**
     * @var string
     */
    protected const BAR_TABLE_ALIAS = 'bar';

    /**
     * @var string
     */
    protected const FOO_CONDITION = 'FOO_CONDITION';

    /**
     * @var string
     */
    protected const IDENTIFIER_TEST_TABLE_ALIAS = 'test_identifiers';

    /**
     * @var string
     */
    protected const IDENTIFIER_TEST_DIFFERENT_VISIBLE_NAME_DEFINITION = '{"identifier":"id_dynamic_entity_configuration","fields":[{"fieldName":"id_dynamic_entity_configuration","fieldVisibleName":"idDynamicEntityConfiguration","isEditable":true,"isCreatable":false,"type":"integer","validation":{"isRequired":false}},{"fieldName":"table_alias","fieldVisibleName":"table_alias","type":"string","isEditable":true,"isCreatable":true,"validation":{"isRequired":false}},{"fieldName":"table_name","fieldVisibleName":"table_name","type":"string","isEditable":true,"isCreatable":true,"validation":{"isRequired":false}},{"fieldName":"is_active","fieldVisibleName":"is_active","isEditable":false,"isCreatable":true,"type":"boolean","validation":{"isRequired":false}},{"fieldName":"definition","fieldVisibleName":"definition","type":"string","isEditable":true,"isCreatable":true,"validation":{"isRequired":false}}]}';

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\DynamicEntityFacadeInterface
     */
    protected $dynamicEntityFacade;

    /**
     * @var \SprykerTest\Zed\DynamicEntity\DynamicEntityBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->dynamicEntityFacade = $this->tester->createDynamicEntityFacade();
        $this->tester->createDynamicEntityConfigurationEntity(
            $this->tester::TABLE_NAME,
            $this->tester::FOO_TABLE_ALIAS_1,
            $this->tester::FOO_DEFINITION,
        );
    }

    /**
     * @return void
     */
    public function testGetDynamicEntityCollectionReturnsNotEmptyCollection(): void
    {
        //Arrange
        $dynamicEntityCriteriaTransfer = $this->tester->haveDynamicEntityCriteriaTransfer($this->tester::FOO_TABLE_ALIAS_1);

        //Act
        $dynamicEntityCollectionTransfer = $this->dynamicEntityFacade->getDynamicEntityCollection($dynamicEntityCriteriaTransfer);

        //Assert
        $totalEntitesCount = SpyDynamicEntityConfigurationQuery::create()->find()->count();
        $this->assertCount($totalEntitesCount, $dynamicEntityCollectionTransfer->getDynamicEntities());
        $this->assertEquals($this->tester::FOO_TABLE_ALIAS_1, $dynamicEntityCollectionTransfer->getDynamicEntities()[$totalEntitesCount - 1]->getFields()[$this->tester::TABLE_ALIAS_FIELD_NAME]);
        $this->assertNotContains('dynamicEntityDefinition', $dynamicEntityCollectionTransfer->getDynamicEntities()[$totalEntitesCount - 1]->getFields());
    }

    /**
     * @return void
     */
    public function testGetDynamicEntityCollectionReturnsFilteredCollection(): void
    {
        //Arrange
        $dynamicEntityCriteriaTransfer = $this->tester->haveDynamicEntityCriteriaTransfer($this->tester::FOO_TABLE_ALIAS_1, $this->tester::FOO_TABLE_ALIAS_1);

        //Act
        $dynamicEntityCollectionTransfer = $this->dynamicEntityFacade->getDynamicEntityCollection($dynamicEntityCriteriaTransfer);

        //Assert
        $this->assertCount(1, $dynamicEntityCollectionTransfer->getDynamicEntities());
        $this->assertEquals($this->tester::FOO_TABLE_ALIAS_1, $dynamicEntityCollectionTransfer->getDynamicEntities()[0]->getFields()[$this->tester::TABLE_ALIAS_FIELD_NAME]);
        $this->assertNotContains('dynamicEntityDefinition', $dynamicEntityCollectionTransfer->getDynamicEntities()[0]->getFields());
    }

    /**
     * @return void
     */
    public function testGetDynamicEntityCollectionReturnsFilteredCollectionByMultipleValues(): void
    {
        //Arrange
        $this->tester->createDynamicEntityConfigurationEntity(
            $this->tester::TABLE_NAME,
            $this->tester::BAR_TABLE_ALIAS,
            $this->tester::FOO_DEFINITION,
        );
        $dynamicEntityCriteriaTransfer = $this->tester->haveDynamicEntityCriteriaTransfer($this->tester::FOO_TABLE_ALIAS_1, $this->tester::IN_FILTER_CONDITION);

        //Act
        $dynamicEntityCollectionTransfer = $this->dynamicEntityFacade->getDynamicEntityCollection($dynamicEntityCriteriaTransfer);

        //Assert
        $this->assertCount(2, $dynamicEntityCollectionTransfer->getDynamicEntities());
        $this->assertEquals($this->tester::BAR_TABLE_ALIAS, $dynamicEntityCollectionTransfer->getDynamicEntities()[0]->getFields()[$this->tester::TABLE_ALIAS_FIELD_NAME]);
        $this->assertEquals($this->tester::FOO_TABLE_ALIAS_1, $dynamicEntityCollectionTransfer->getDynamicEntities()[1]->getFields()[$this->tester::TABLE_ALIAS_FIELD_NAME]);
        $this->assertNotContains('dynamicEntityDefinition', $dynamicEntityCollectionTransfer->getDynamicEntities()[0]->getFields());
    }

    /**
     * @return void
     */
    public function testGetDynamicEntityCollectionReturnsEmptyCollection(): void
    {
        //Arrange
        $dynamicEntityCriteriaTransfer = $this->tester->haveDynamicEntityCriteriaTransfer($this->tester::FOO_TABLE_ALIAS_1, static::FOO_CONDITION);

        //Act
        $dynamicEntityCollectionTransfer = $this->dynamicEntityFacade->getDynamicEntityCollection($dynamicEntityCriteriaTransfer);

        //Assert
        $this->assertEmpty($dynamicEntityCollectionTransfer->getDynamicEntities());
    }

    /**
     * @return void
     */
    public function testGetDynamicEntityCollectionReturnsCollectionWithoutChild(): void
    {
        // Arrange
        $dynamicEntityConfigurationEntity = $this->tester->createDynamicEntityConfigurationWithRelationAndFieldMapping(
            $this->tester::FOO_TABLE_ALIAS_1,
            static::RELATION_TEST_NAME,
            static::FIELD_PARENT_ENTITY_ID_CONFIGURATION,
            static::FIELD_CHILD_ENTITY_ID_CONFIGURATION,
        );

        $dynamicEntityCriteriaTransfer = $this->tester->haveDynamicEntityCriteriaTransfer($this->tester::FOO_TABLE_ALIAS_1);

        // Act
        $dynamicEntityCollectionTransfer = $this->dynamicEntityFacade->getDynamicEntityCollection($dynamicEntityCriteriaTransfer);
        $dynamicEntityTranfer = $this->tester->getDynamicEntityFromCollectionByFieldNameAndValue($dynamicEntityCollectionTransfer, $this->tester::TABLE_ALIAS_FIELD_NAME, 'FOO');

        // Assert
        $this->assertEquals($dynamicEntityTranfer->getFields()[static::FIELD_PARENT_ENTITY_ID_CONFIGURATION], $dynamicEntityConfigurationEntity->getIdDynamicEntityConfiguration());
        $this->assertEmpty($dynamicEntityTranfer->getChildRelations());
    }

    /**
     * @return void
     */
    public function testGetDynamicEntityCollectionReturnsCollectionWithChildRelations(): void
    {
        // Arrange
        $dynamicEntityConfigurationEntity = $this->tester->createDynamicEntityConfigurationWithRelationAndFieldMapping(
            $this->tester::FOO_TABLE_ALIAS_1,
            static::RELATION_TEST_NAME,
            static::FIELD_PARENT_ENTITY_ID_CONFIGURATION,
            static::FIELD_CHILD_ENTITY_ID_CONFIGURATION,
        );

        $dynamicEntityCriteriaTransfer = $this->tester->haveDynamicEntityCriteriaTransfer($this->tester::FOO_TABLE_ALIAS_1);
        $dynamicEntityCriteriaTransfer->setRelationChains([static::RELATION_TEST_NAME]);

        // Act
        $dynamicEntityCollectionTransfer = $this->dynamicEntityFacade->getDynamicEntityCollection($dynamicEntityCriteriaTransfer);
        $dynamicEntityTranfer = $this->tester->getDynamicEntityFromCollectionByFieldNameAndValue($dynamicEntityCollectionTransfer, $this->tester::TABLE_ALIAS_FIELD_NAME, 'FOO');

        // Assert
        $this->assertEquals($dynamicEntityTranfer->getFields()[static::FIELD_PARENT_ENTITY_ID_CONFIGURATION], $dynamicEntityConfigurationEntity->getIdDynamicEntityConfiguration());
        $this->assertNotEmpty($dynamicEntityTranfer->getChildRelations());
        $this->assertEquals(1, count($dynamicEntityTranfer->getChildRelations()));
        $this->assertNotEmpty($dynamicEntityTranfer->getChildRelations()[0]);
        $this->assertInstanceOf(DynamicEntityRelationTransfer::class, $dynamicEntityTranfer->getChildRelations()[0]);
        $this->assertEquals(static::RELATION_TEST_NAME, $dynamicEntityTranfer->getChildRelations()[0]->getName());
        $this->assertNotEmpty($dynamicEntityTranfer->getChildRelations()[0]->getDynamicEntities());
        $this->assertEquals(1, count($dynamicEntityTranfer->getChildRelations()[0]->getDynamicEntities()));
    }

    /**
     * @return void
     */
    public function testCreateDynamicEntityCollectionCreatesTheRecord(): void
    {
        //Arrange
        $dynamicEntityCollectionRequestTransfer = $this->tester->createDynamicEntityCollectionRequestTransfer($this->tester::FOO_TABLE_ALIAS_1);
        $dynamicEntityCollectionRequestTransfer->addDynamicEntity(
            (new DynamicEntityTransfer())
                ->setFields([
                    $this->tester::TABLE_ALIAS_FIELD_NAME => static::FOO_TABLE_ALIAS_2,
                    'table_name' => static::FOO_TABLE_NAME,
                    'is_active' => true,
                    'definition' => $this->tester::FOO_DEFINITION,
                ]),
        );

        //Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->createDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);

        //Assert
        $this->assertEmpty($dynamicEntityCollectionResponseTransfer->getErrors());

        $dynamicConfigurationEntity = $this->tester->getDynamicEntityConfigurationByTableName(static::FOO_TABLE_NAME);
        $this->assertEquals(static::FOO_TABLE_ALIAS_2, $dynamicConfigurationEntity->getTableAlias());
    }

    /**
     * @return void
     */
    public function testCreateDynamicEntityCollectionCreatesTheRecordWithDefaultFieldValueIfItIsNotPassed(): void
    {
        //Arrange
        $tableAliasUniq = uniqid();
        $tableNameUniq = 'spy_' . $tableAliasUniq;
        $dynamicEntityCollectionRequestTransfer = $this->tester->createDynamicEntityCollectionRequestTransfer($this->tester::FOO_TABLE_ALIAS_1);
        $dynamicEntityCollectionRequestTransfer->addDynamicEntity(
            (new DynamicEntityTransfer())
                ->setFields([
                    $this->tester::TABLE_ALIAS_FIELD_NAME => $tableAliasUniq,
                    'table_name' => $tableNameUniq,
                    'definition' => $this->tester::FOO_DEFINITION,
                ]),
        );

        //Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->createDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);

        //Assert
        $dynamicConfigurationEntity = $this->tester->getDynamicEntityConfigurationByTableName($tableNameUniq);
        $this->assertEquals(false, $dynamicConfigurationEntity->getIsActive());
    }

    /**
     * @return void
     */
    public function testCreateDynamicEntityCollectionReturnErrorIfInvalidDataTypeIsPassed(): void
    {
        //Arrange
        $dynamicEntityCollectionRequestTransfer = $this->tester->createDynamicEntityCollectionRequestTransfer($this->tester::FOO_TABLE_ALIAS_1);
        $dynamicEntityCollectionRequestTransfer->addDynamicEntity(
            (new DynamicEntityTransfer())
                ->setFields([
                    $this->tester::TABLE_ALIAS_FIELD_NAME => $this->tester::FOO_TABLE_ALIAS_1,
                    'table_name' => true,
                    'is_active' => true,
                    'definition' => $this->tester::FOO_DEFINITION,
                ]),
        );

        //Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->createDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);

        //Assert
        $this->assertNotEmpty($dynamicEntityCollectionResponseTransfer->getErrors());
        $this->assertEquals(
            static::ERROR_INVALID_DATA_TYPE,
            $dynamicEntityCollectionResponseTransfer->getErrors()[0]->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testCreateDynamicEntityCollectionReturnErrorIfInvalidFieldNameIsProvided(): void
    {
        //Arrange
        $dynamicEntityCollectionRequestTransfer = $this->tester->createDynamicEntityCollectionRequestTransfer($this->tester::FOO_TABLE_ALIAS_1);
        $dynamicEntityCollectionRequestTransfer->addDynamicEntity(
            (new DynamicEntityTransfer())
                ->setFields([
                    $this->tester::TABLE_ALIAS_FIELD_NAME => $this->tester::FOO_TABLE_ALIAS_1,
                    'table_name' => true,
                    'is_active' => true,
                    'definition_foo' => $this->tester::FOO_DEFINITION,
                ]),
        );

        //Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->createDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);

        //Assert
        $this->assertNotEmpty($dynamicEntityCollectionResponseTransfer->getErrors());
        $this->assertEquals(
            static::ERROR_PROVIDED_FIELD_IS_INVALID,
            $dynamicEntityCollectionResponseTransfer->getErrors()[0]->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testCreateDynamicEntityConfigurationCollectionExecutesDynamicEntityPostCreatePlugins(): void
    {
        // Arrange
        $dynamicEntityCollectionRequestTransfer = $this->tester->createDynamicEntityCollectionRequestTransfer($this->tester::FOO_TABLE_ALIAS_1);
        $dynamicEntityCollectionRequestTransfer->addDynamicEntity(
            (new DynamicEntityTransfer())
                ->setFields([
                    $this->tester::TABLE_ALIAS_FIELD_NAME => static::FOO_TABLE_ALIAS_2,
                    'table_name' => static::FOO_TABLE_NAME,
                    'is_active' => true,
                    'definition' => $this->tester::FOO_DEFINITION,
                ]),
        );

        $dynamicEntityPostCreatePluginMock = $this
            ->getMockBuilder(DynamicEntityPostCreatePluginInterface::class)
            ->getMock();

        $dynamicEntityPostCreatePluginMock
            ->expects($this->once())
            ->method('postCreate')
            ->willReturn(new DynamicEntityPostEditResponseTransfer());

        $this->tester->setDependency(
            DynamicEntityDependencyProvider::PLUGINS_DYNAMIC_ENTITY_POST_CREATE,
            [$dynamicEntityPostCreatePluginMock],
        );

        // Act
        $this->dynamicEntityFacade->createDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateDynamicEntityCollectionUpdatesTheRecord(): void
    {
        //Arrange
        $dynamicConfigurationEntity = $this->tester->getDynamicEntityConfigurationByTableAlias($this->tester::FOO_TABLE_ALIAS_1);

        $dynamicEntityCollectionRequestTransfer = $this->tester->createDynamicEntityCollectionRequestTransfer($this->tester::FOO_TABLE_ALIAS_1);
        $dynamicEntityCollectionRequestTransfer->addDynamicEntity(
            (new DynamicEntityTransfer())
                ->setFields([
                    'id_dynamic_entity_configuration' => $dynamicConfigurationEntity->getIdDynamicEntityConfiguration(),
                    'table_name' => static::FOO_TABLE_NAME,
                ]),
        );

        //Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->updateDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);

        //Assert
        $updatedDynamicConfigurationEntity = $this->tester->getDynamicEntityConfigurationByIdDynamicEntityConfiguration(
            $dynamicConfigurationEntity->getIdDynamicEntityConfiguration(),
        );
        $this->assertEmpty($dynamicEntityCollectionResponseTransfer->getErrors());
        $this->assertNotEquals($dynamicConfigurationEntity->getTableName(), $updatedDynamicConfigurationEntity->getTableName());
        $this->assertEquals(static::FOO_TABLE_NAME, $updatedDynamicConfigurationEntity->getTableName());
    }

    /**
     * @return void
     */
    public function testPatchDynamicEntityCollectionUpdatesCollectionWithChildRelations(): void
    {
        // Arrange
        $dynamicEntityConfigurationEntity = $this->tester->createDynamicEntityConfigurationWithRelationAndFieldMapping(
            $this->tester::FOO_TABLE_ALIAS_1,
            static::RELATION_TEST_NAME,
            static::FIELD_PARENT_ENTITY_ID_CONFIGURATION,
            static::FIELD_CHILD_ENTITY_ID_CONFIGURATION,
        );
        $dynamicConfigurationEntity = $this->tester->getDynamicEntityConfigurationByTableAlias($this->tester::FOO_TABLE_ALIAS_1);
        $relationEntity = $this->tester->getDynamicEntityConfigurationRelationEntityByRelation(static::RELATION_TEST_NAME);
        $childDynamicConfigurationEntity = $relationEntity->getSpyDynamicEntityConfigurationRelatedByFkChildDynamicEntityConfiguration();

        $dynamicEntityCollectionRequestTransfer = $this->tester->createDynamicEntityCollectionRequestTransfer($dynamicEntityConfigurationEntity->getTableAlias());
        $dynamicEntityCollectionRequestTransfer
            ->setIsCreatable(false)
            ->addDynamicEntity(
                (new DynamicEntityTransfer())
                    ->setFields([
                        'id_dynamic_entity_configuration' => $dynamicConfigurationEntity->getIdDynamicEntityConfiguration(),
                        'table_name' => static::FOO_TABLE_NAME,
                        static::RELATION_TEST_NAME => [
                            [
                                'id_dynamic_entity_configuration_relation' => $relationEntity->getIdDynamicEntityConfigurationRelation(),
                                'fk_parent_dynamic_entity_configuration' => $dynamicConfigurationEntity->getIdDynamicEntityConfiguration(),
                                'fk_child_dynamic_entity_configuration' => 1,
                                'is_editable' => true,
                                'name' => 'testme',
                            ],
                        ],
                    ]),
            );

        // Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->updateDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);

        // Assert
        $updatedDynamicConfigurationEntity = $this->tester->getDynamicEntityConfigurationByIdDynamicEntityConfiguration(
            $childDynamicConfigurationEntity->getIdDynamicEntityConfiguration(),
        );
        $this->assertEmpty($dynamicEntityCollectionResponseTransfer->getErrors());
        $this->assertNotEquals($dynamicConfigurationEntity->getTableName(), $updatedDynamicConfigurationEntity->getTableName());
    }

    /**
     * @return void
     */
    public function testPatchDynamicEntityCollectionWithChildRelationsHasErrorsWhenWrongData(): void
    {
        // Arrange
        $dynamicEntityConfigurationEntity = $this->tester->createDynamicEntityConfigurationWithRelationAndFieldMapping(
            $this->tester::FOO_TABLE_ALIAS_1,
            static::RELATION_TEST_NAME,
            static::FIELD_PARENT_ENTITY_ID_CONFIGURATION,
            static::FIELD_CHILD_ENTITY_ID_CONFIGURATION,
        );

        $dynamicConfigurationEntity = $this->tester->getDynamicEntityConfigurationByTableAlias($this->tester::FOO_TABLE_ALIAS_1);
        $dynamicEntityCollectionRequestTransfer = $this->tester->createDynamicEntityCollectionRequestTransfer($dynamicEntityConfigurationEntity->getTableAlias());
        $dynamicEntityCollectionRequestTransfer
            ->setIsCreatable(false)
            ->addDynamicEntity(
                (new DynamicEntityTransfer())
                    ->setFields([
                        'id_dynamic_entity_configuration' => $dynamicConfigurationEntity->getIdDynamicEntityConfiguration(),
                        'table_name' => static::FOO_TABLE_NAME,
                        static::RELATION_TEST_NAME => [
                            [
                                'id_dynamic_entity_configuration' => $dynamicConfigurationEntity->getIdDynamicEntityConfiguration(),
                                'table_name' => static::FOO_TABLE_NAME,
                            ],
                        ],
                    ]),
            );

        // Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->updateDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);

        // Assert
        $this->assertNotEmpty($dynamicEntityCollectionResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testPutDynamicEntityCollectionUpdatesCollectionWithChildRelations(): void
    {
        // Arrange
        $dynamicEntityConfigurationEntity = $this->tester->createDynamicEntityConfigurationWithRelationAndFieldMapping(
            $this->tester::FOO_TABLE_ALIAS_1,
            static::RELATION_TEST_NAME,
            static::FIELD_PARENT_ENTITY_ID_CONFIGURATION,
            static::FIELD_CHILD_ENTITY_ID_CONFIGURATION,
        );
        $dynamicConfigurationEntity = $this->tester->getDynamicEntityConfigurationByTableAlias($this->tester::FOO_TABLE_ALIAS_1);
        $relationEntity = $this->tester->getDynamicEntityConfigurationRelationEntityByRelation(static::RELATION_TEST_NAME);
        $childDynamicConfigurationEntity = $relationEntity->getSpyDynamicEntityConfigurationRelatedByFkChildDynamicEntityConfiguration();

        $dynamicEntityCollectionRequestTransfer = $this->tester->createDynamicEntityCollectionRequestTransfer($dynamicEntityConfigurationEntity->getTableAlias());
        $dynamicEntityCollectionRequestTransfer
            ->setIsCreatable(true)
            ->addDynamicEntity(
                (new DynamicEntityTransfer())
                    ->setFields([
                        'id_dynamic_entity_configuration' => $dynamicConfigurationEntity->getIdDynamicEntityConfiguration(),
                        'table_name' => static::FOO_TABLE_NAME,
                        static::RELATION_TEST_NAME => [
                            [
                                'id_dynamic_entity_configuration_relation' => $relationEntity->getIdDynamicEntityConfigurationRelation(),
                                'fk_parent_dynamic_entity_configuration' => $dynamicConfigurationEntity->getIdDynamicEntityConfiguration(),
                                'fk_child_dynamic_entity_configuration' => 1,
                                'is_editable' => true,
                                'name' => 'testme',
                            ],
                        ],
                    ]),
            );

        // Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->updateDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);

        // Assert
        $updatedDynamicConfigurationEntity = $this->tester->getDynamicEntityConfigurationByIdDynamicEntityConfiguration(
            $childDynamicConfigurationEntity->getIdDynamicEntityConfiguration(),
        );
        $this->assertEmpty($dynamicEntityCollectionResponseTransfer->getErrors());
        $this->assertNotEquals($dynamicConfigurationEntity->getTableName(), $updatedDynamicConfigurationEntity->getTableName());
    }

    /**
     * @return void
     */
    public function testPutDynamicEntityCollectionWithChildRelationsHasErrorsWhenWrongData(): void
    {
        // Arrange
        $dynamicEntityConfigurationEntity = $this->tester->createDynamicEntityConfigurationWithRelationAndFieldMapping(
            $this->tester::FOO_TABLE_ALIAS_1,
            static::RELATION_TEST_NAME,
            static::FIELD_PARENT_ENTITY_ID_CONFIGURATION,
            static::FIELD_CHILD_ENTITY_ID_CONFIGURATION,
        );

        $dynamicConfigurationEntity = $this->tester->getDynamicEntityConfigurationByTableAlias($this->tester::FOO_TABLE_ALIAS_1);
        $dynamicEntityCollectionRequestTransfer = $this->tester->createDynamicEntityCollectionRequestTransfer($dynamicEntityConfigurationEntity->getTableAlias());
        $dynamicEntityCollectionRequestTransfer
            ->setIsCreatable(true)
            ->addDynamicEntity(
                (new DynamicEntityTransfer())
                    ->setFields([
                        'id_dynamic_entity_configuration' => $dynamicConfigurationEntity->getIdDynamicEntityConfiguration(),
                        'table_name' => static::FOO_TABLE_NAME,
                        static::RELATION_TEST_NAME => [
                            [
                                'id_dynamic_entity_configuration' => $dynamicConfigurationEntity->getIdDynamicEntityConfiguration(),
                                'table_name' => static::FOO_TABLE_NAME,
                            ],
                        ],
                    ]),
            );

        // Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->updateDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);

        // Assert
        $this->assertNotEmpty($dynamicEntityCollectionResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testUpdateDynamicEntityCollectionReturnsErrorIfInvalidDataTypeIsPassed(): void
    {
        //Arrange
        $dynamicConfigurationEntity = $this->tester->getDynamicEntityConfigurationByTableAlias($this->tester::FOO_TABLE_ALIAS_1);

        $dynamicEntityCollectionRequestTransfer = $this->tester->createDynamicEntityCollectionRequestTransfer($this->tester::FOO_TABLE_ALIAS_1);
        $dynamicEntityCollectionRequestTransfer->addDynamicEntity(
            (new DynamicEntityTransfer())
                ->setFields([
                    'id_dynamic_entity_configuration' => $dynamicConfigurationEntity->getIdDynamicEntityConfiguration(),
                    'table_name' => 4,
                ]),
        );

        //Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->updateDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);

        //Assert
        $this->assertNotEmpty($dynamicEntityCollectionResponseTransfer->getErrors());
        $this->assertEquals(
            static::ERROR_INVALID_DATA_TYPE,
            $dynamicEntityCollectionResponseTransfer->getErrors()[0]->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testUpdateDynamicEntityCollectionReturnsErrorIfInvalidFieldNameIsProvided(): void
    {
        //Arrange
        $dynamicConfigurationEntity = $this->tester->getDynamicEntityConfigurationByTableAlias($this->tester::FOO_TABLE_ALIAS_1);

        $dynamicEntityCollectionRequestTransfer = $this->tester->createDynamicEntityCollectionRequestTransfer($this->tester::FOO_TABLE_ALIAS_1);
        $dynamicEntityCollectionRequestTransfer->addDynamicEntity(
            (new DynamicEntityTransfer())
                ->setFields([
                    'id_dynamic_entity_configuration' => $dynamicConfigurationEntity->getIdDynamicEntityConfiguration(),
                    'table_name_foo' => static::FOO_TABLE_NAME,
                ]),
        );

        //Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->updateDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);

        //Assert
        $this->assertNotEmpty($dynamicEntityCollectionResponseTransfer->getErrors());
        $this->assertEquals(
            static::ERROR_PROVIDED_FIELD_IS_INVALID,
            $dynamicEntityCollectionResponseTransfer->getErrors()[0]->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testUpdateDynamicEntityCollectionDoesNotUpdateIfFieldIsNotEditable(): void
    {
        //Arrange
        $dynamicConfigurationEntity = $this->tester->getDynamicEntityConfigurationByTableAlias($this->tester::FOO_TABLE_ALIAS_1);

        $dynamicEntityCollectionRequestTransfer = $this->tester->createDynamicEntityCollectionRequestTransfer($this->tester::FOO_TABLE_ALIAS_1);
        $dynamicEntityCollectionRequestTransfer->addDynamicEntity(
            (new DynamicEntityTransfer())
                ->setFields([
                    'id_dynamic_entity_configuration' => $dynamicConfigurationEntity->getIdDynamicEntityConfiguration(),
                    'is_active' => false,
                ]),
        );

        //Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->updateDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);

        //Assert
        $updatedDynamicConfigurationEntity = $this->tester->getDynamicEntityConfigurationByIdDynamicEntityConfiguration(
            $dynamicConfigurationEntity->getIdDynamicEntityConfiguration(),
        );

        $this->assertNotEmpty($dynamicEntityCollectionResponseTransfer->getErrors());
        $this->assertEquals(
            static::ERROR_MODIFICATION_OF_IMMUTABLE_FIELD_PROHIBITED,
            $dynamicEntityCollectionResponseTransfer->getErrors()[0]->getMessage(),
        );
        $this->assertTrue($updatedDynamicConfigurationEntity->getIsActive());
        $this->assertEquals($dynamicConfigurationEntity->getIsActive(), $updatedDynamicConfigurationEntity->getIsActive());
    }

    /**
     * @return void
     */
    public function testUpdateDynamicEntityConfigurationCollectionExecutesDynamicEntityPostUpdatePlugins(): void
    {
        // Arrange
        $dynamicConfigurationEntity = $this->tester->getDynamicEntityConfigurationByTableAlias($this->tester::FOO_TABLE_ALIAS_1);

        $dynamicEntityCollectionRequestTransfer = $this->tester->createDynamicEntityCollectionRequestTransfer($this->tester::FOO_TABLE_ALIAS_1);
        $dynamicEntityCollectionRequestTransfer->addDynamicEntity(
            (new DynamicEntityTransfer())
                ->setFields([
                    'id_dynamic_entity_configuration' => $dynamicConfigurationEntity->getIdDynamicEntityConfiguration(),
                    'table_name' => static::FOO_TABLE_NAME,
                ]),
        );

        $dynamicEntityPostUpdatePluginMock = $this
            ->getMockBuilder(DynamicEntityPostUpdatePluginInterface::class)
            ->getMock();

        $dynamicEntityPostUpdatePluginMock
            ->expects($this->once())
            ->method('postUpdate')
            ->willReturn(new DynamicEntityPostEditResponseTransfer());

        $this->tester->setDependency(
            DynamicEntityDependencyProvider::PLUGINS_DYNAMIC_ENTITY_POST_UPDATE,
            [$dynamicEntityPostUpdatePluginMock],
        );

        // Act
        $this->dynamicEntityFacade->updateDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testGetConfigurationReturnCollectionOfActiveConfigurations(): void
    {
        //Arrange
        $dynamicEntityConfigurationCriteriaTransfer = new DynamicEntityConfigurationCriteriaTransfer();
        $dynamicEntityConfigurationCriteriaTransfer->setDynamicEntityConfigurationConditions(
            (new DynamicEntityConfigurationConditionsTransfer())
                ->setIsActive(true),
        );

        //Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->getDynamicEntityConfigurationCollection($dynamicEntityConfigurationCriteriaTransfer);

        //Assert
        $this->assertNotEmpty($dynamicEntityCollectionResponseTransfer->getDynamicEntityConfigurations());
        $dynamicEntityConfigurationTransfer = $dynamicEntityCollectionResponseTransfer->getDynamicEntityConfigurations()[count($dynamicEntityCollectionResponseTransfer->getDynamicEntityConfigurations()) - 1];
        $this->assertTrue($dynamicEntityConfigurationTransfer->getIsActive());
        $this->assertEquals($this->tester::FOO_TABLE_ALIAS_1, $dynamicEntityConfigurationTransfer->getTableAlias());
    }

    /**
     * @dataProvider getDynamicEntityConfigurationJsonDataProvider
     *
     * @param string $dynamicEntityConfigurationJsonFilename
     * @param int $expectedNumberOfDynamicEntityValidConfigurations
     * @param int $expectedNumberOfDynamicEntityInvalidConfigurations
     * @param int $expectedNumberOfDynamicEntityChildRelations
     *
     * @return void
     */
    public function testInstallPersistsConfigurations(
        string $dynamicEntityConfigurationJsonFilename,
        int $expectedNumberOfDynamicEntityValidConfigurations,
        int $expectedNumberOfDynamicEntityInvalidConfigurations,
        int $expectedNumberOfDynamicEntityChildRelations
    ): void {
        // Arrange
        $this->createBusinessFactoryMock($dynamicEntityConfigurationJsonFilename);

        // Act
        $this->dynamicEntityFacade->install();

        // Assert
        $this->assertCount(
            $expectedNumberOfDynamicEntityValidConfigurations,
            SpyDynamicEntityConfigurationQuery::create()
                ->filterByTableAlias('test')
                ->find(),
        );
        $this->assertCount(
            $expectedNumberOfDynamicEntityInvalidConfigurations,
            SpyDynamicEntityConfigurationQuery::create()
                ->filterByTableName(static::FOO_TABLE_NAME)
                ->find(),
        );
        $this->assertCount(
            $expectedNumberOfDynamicEntityChildRelations,
            SpyDynamicEntityConfigurationRelationQuery::create()
                ->filterByName('childTestBar')
                ->find(),
        );
    }

    /**
     * @return array<mixed>
     */
    public function getDynamicEntityConfigurationJsonDataProvider(): array
    {
        return [
            'Should add a new entity configuration with child relations' => [
                'configuration.json', 1, 0, 1,
            ],
            'Should add a new entity configuration without child relations' => [
                'configuration_invalid.json', 1, 0, 0,
            ],
        ];
    }

    /**
     * @return void
     */
    public function testCreateDynamicEntityCollectionCreatesTheRecordAndReturnsValidReponseTransfer(): void
    {
        //Arrange
        $resourceName = 'resource-1';
        $tableName = 'spy_resource_1';
        $dynamicEntityCollectionRequestTransfer = $this->tester->createDynamicEntityCollectionRequestTransfer($this->tester::FOO_TABLE_ALIAS_1);
        $dynamicEntityCollectionRequestTransfer->addDynamicEntity(
            (new DynamicEntityTransfer())
                ->setFields([
                    $this->tester::TABLE_ALIAS_FIELD_NAME => $resourceName,
                    'table_name' => $tableName,
                    'definition' => '{}',
                ]),
        );

        //Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->createDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);

        //Assert
        $this->assertEmpty($dynamicEntityCollectionResponseTransfer->getErrors());

        $dynamicEntityConfigurationEntity = $this->tester->getDynamicEntityConfigurationByTableName($tableName);
        $this->assertEquals($resourceName, $dynamicEntityConfigurationEntity->getTableAlias());
        $this->assertIsNumeric($dynamicEntityCollectionResponseTransfer->getDynamicEntities()[0]->getFields()['id_dynamic_entity_configuration']);
        $this->assertEquals($dynamicEntityConfigurationEntity->getTableAlias(), $dynamicEntityCollectionResponseTransfer->getDynamicEntities()[0]->getFields()[$this->tester::TABLE_ALIAS_FIELD_NAME]);
        $this->assertEquals($dynamicEntityConfigurationEntity->getTableName(), $dynamicEntityCollectionResponseTransfer->getDynamicEntities()[0]->getFields()['table_name']);
        $this->assertEquals($dynamicEntityConfigurationEntity->getDefinition(), $dynamicEntityCollectionResponseTransfer->getDynamicEntities()[0]->getFields()['definition']);
    }

    /**
     * @return void
     */
    public function testUpdateDynamicEntityCollectionUpdatesTheRecordAndReturnsCorrectResponseTransfer(): void
    {
        //Arrange
        $dynamicConfigurationEntity = $this->tester->getDynamicEntityConfigurationByTableAlias($this->tester::FOO_TABLE_ALIAS_1);

        $dynamicEntityCollectionRequestTransfer = $this->tester->createDynamicEntityCollectionRequestTransfer($this->tester::FOO_TABLE_ALIAS_1);
        $dynamicEntityCollectionRequestTransfer->addDynamicEntity(
            (new DynamicEntityTransfer())
                ->setFields([
                    'id_dynamic_entity_configuration' => $dynamicConfigurationEntity->getIdDynamicEntityConfiguration(),
                    'table_name' => static::FOO_TABLE_NAME,
                ]),
        );

        //Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->updateDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);

        //Assert
        $updatedDynamicConfigurationEntity = $this->tester->getDynamicEntityConfigurationByIdDynamicEntityConfiguration(
            $dynamicConfigurationEntity->getIdDynamicEntityConfiguration(),
        );
        $updateCollectionResponseFields = $dynamicEntityCollectionResponseTransfer->getDynamicEntities()[0]->getFields();
        $this->assertEmpty($dynamicEntityCollectionResponseTransfer->getErrors());
        $this->assertNotEquals($dynamicConfigurationEntity->getTableName(), $updatedDynamicConfigurationEntity->getTableName());
        $this->assertEquals(static::FOO_TABLE_NAME, $updatedDynamicConfigurationEntity->getTableName());
        $this->assertEquals($updatedDynamicConfigurationEntity->getTableName(), $updateCollectionResponseFields['table_name']);
        $this->assertEquals($updatedDynamicConfigurationEntity->getTableAlias(), $updateCollectionResponseFields[$this->tester::TABLE_ALIAS_FIELD_NAME]);
        $this->assertEquals($updatedDynamicConfigurationEntity->getDefinition(), $updateCollectionResponseFields['definition']);
    }

    /**
     * @return void
     */
    public function testUpdateDynamicEntityCollectionUpdatesTheRecordWithNonDefaultIdentifierVisibleName(): void
    {
        //Arrange
        $this->tester->createDynamicEntityConfigurationEntity(
            $this->tester::TABLE_NAME,
            static::IDENTIFIER_TEST_TABLE_ALIAS,
            static::IDENTIFIER_TEST_DIFFERENT_VISIBLE_NAME_DEFINITION,
        );
        $dynamicConfigurationEntity = $this->tester->getDynamicEntityConfigurationByTableAlias(static::IDENTIFIER_TEST_TABLE_ALIAS);

        $dynamicEntityCollectionRequestTransfer = $this->tester->createDynamicEntityCollectionRequestTransfer(static::IDENTIFIER_TEST_TABLE_ALIAS);
        $dynamicEntityCollectionRequestTransfer->addDynamicEntity(
            (new DynamicEntityTransfer())
                ->setFields([
                    'idDynamicEntityConfiguration' => $dynamicConfigurationEntity->getIdDynamicEntityConfiguration(),
                    'table_name' => 'newid',
                ]),
        );

        //Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->updateDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);

        //Assert
        $updatedDynamicConfigurationEntity = $this->tester->getDynamicEntityConfigurationByIdDynamicEntityConfiguration(
            $dynamicConfigurationEntity->getIdDynamicEntityConfiguration(),
        );
        $this->assertEmpty($dynamicEntityCollectionResponseTransfer->getErrors());
        $this->assertNotEquals($dynamicConfigurationEntity->getTableName(), $updatedDynamicConfigurationEntity->getTableName());
        $this->assertEquals('newid', $updatedDynamicConfigurationEntity->getTableName());
        $this->assertEquals($updatedDynamicConfigurationEntity->getTableName(), $dynamicEntityCollectionResponseTransfer->getDynamicEntities()[0]->getFields()['table_name']);
    }

    /**
     * @return void
     */
    public function testCreateDynamicEntityConfigurationCollectionWillReturnCollectionWithoutErrors(): void
    {
        // Arrange
        $dynamicEntityConfigurationCollectionRequestTransfer = $this->tester->createDynamicEntityConfigurationCollectionRequestTransfer();
        $requestDynamicEntityConfigurationTransfer = $dynamicEntityConfigurationCollectionRequestTransfer->getDynamicEntityConfigurations()[0];

        // Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->createDynamicEntityConfigurationCollection($dynamicEntityConfigurationCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $dynamicEntityCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $dynamicEntityCollectionResponseTransfer->getDynamicEntityConfigurations());
        $responseDynamicEntityConfigurationTransfer = $dynamicEntityCollectionResponseTransfer->getDynamicEntityConfigurations()[0];
        $this->assertNotNull($responseDynamicEntityConfigurationTransfer->getIdDynamicEntityConfiguration());
        $this->assertTrue($responseDynamicEntityConfigurationTransfer->getIsActive());
        $this->assertEquals($requestDynamicEntityConfigurationTransfer->getTableAlias(), $responseDynamicEntityConfigurationTransfer->getTableAlias());
        $this->assertEquals($requestDynamicEntityConfigurationTransfer->getTableName(), $responseDynamicEntityConfigurationTransfer->getTableName());
        $this->assertEquals($requestDynamicEntityConfigurationTransfer->getDynamicEntityDefinition(), $responseDynamicEntityConfigurationTransfer->getDynamicEntityDefinition());
    }

    /**
     * @return void
     */
    public function testCreateDynamicEntityConfigurationCollectionWillReturnCollectionWithoutErrorsAndAssertDatabaseEntity(): void
    {
        // Arrange
        $dynamicEntityConfigurationCollectionRequestTransfer = $this->tester->createDynamicEntityConfigurationCollectionRequestTransfer();
        $requestDynamicEntityConfigurationTransfer = $dynamicEntityConfigurationCollectionRequestTransfer->getDynamicEntityConfigurations()[0];

        // Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->createDynamicEntityConfigurationCollection($dynamicEntityConfigurationCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $dynamicEntityCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $dynamicEntityCollectionResponseTransfer->getDynamicEntityConfigurations());
        $responseDynamicEntityConfigurationTransfer = $dynamicEntityCollectionResponseTransfer->getDynamicEntityConfigurations()[0];
        $entity = $this->tester->findDynamicEntityConfigurationById($responseDynamicEntityConfigurationTransfer->getIdDynamicEntityConfiguration());
        $this->assertNotNull($entity);
        $this->assertTrue($entity->getIsActive());
        $this->assertEquals($requestDynamicEntityConfigurationTransfer->getTableAlias(), $entity->getTableAlias());
        $this->assertEquals($requestDynamicEntityConfigurationTransfer->getTableName(), $entity->getTableName());
        $this->assertEquals($this->tester->getExpectedDefinition(), $entity->getDefinition());
    }

    /**
     * @return void
     */
    public function testUpdateDynamicEntityConfigurationCollectionWillReturnCollectionWithCorrectTransferWithoutErrors(): void
    {
        // Arrange
        $id = $this->tester->createEntity('spy_table_for_update', 'table-for-update');
        $dynamicEntityConfigurationCollectionRequestTransfer = $this->tester->createDynamicEntityConfigurationCollectionRequestTransfer(static::BAR_TABLE_NAME, static::BAR_TABLE_ALIAS, $id);

        // Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->updateDynamicEntityConfigurationCollection($dynamicEntityConfigurationCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $dynamicEntityCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $dynamicEntityCollectionResponseTransfer->getDynamicEntityConfigurations());
        $responseDynamicEntityConfigurationTransfer = $dynamicEntityCollectionResponseTransfer->getDynamicEntityConfigurations()[0];
        $this->assertEquals($id, $responseDynamicEntityConfigurationTransfer->getIdDynamicEntityConfiguration());
        $this->assertTrue($responseDynamicEntityConfigurationTransfer->getIsActive());
        $this->assertEquals(static::BAR_TABLE_NAME, $responseDynamicEntityConfigurationTransfer->getTableName());
        $this->assertEquals(static::BAR_TABLE_ALIAS, $responseDynamicEntityConfigurationTransfer->getTableAlias());
    }

    /**
     * @return void
     */
    public function testUpdateDynamicEntityConfigurationCollectionWillReturnCollectionWithCorrectTransferWithoutErrorsAndAssertDatabaseEntity(): void
    {
        // Arrange
        $id = $this->tester->createEntity('spy_table_for_update', 'table-for-update');
        $dynamicEntityConfigurationCollectionRequestTransfer = $this->tester->createDynamicEntityConfigurationCollectionRequestTransfer(static::BAR_TABLE_NAME, static::BAR_TABLE_ALIAS, $id);

        // Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->updateDynamicEntityConfigurationCollection($dynamicEntityConfigurationCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $dynamicEntityCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $dynamicEntityCollectionResponseTransfer->getDynamicEntityConfigurations());
        $responseDynamicEntityConfigurationTransfer = $dynamicEntityCollectionResponseTransfer->getDynamicEntityConfigurations()[0];
        $entity = $this->tester->findDynamicEntityConfigurationById($responseDynamicEntityConfigurationTransfer->getIdDynamicEntityConfiguration());
        $this->assertNotNull($entity);
        $this->assertTrue($entity->getIsActive());
        $this->assertEquals(static::BAR_TABLE_NAME, $entity->getTableName());
        $this->assertEquals(static::BAR_TABLE_ALIAS, $entity->getTableAlias());
        $this->assertEquals($this->tester->getExpectedDefinition(), $entity->getDefinition());
    }

    /**
     * @param string $configurationFilename
     *
     * @return void
     */
    protected function createBusinessFactoryMock(string $configurationFilename): void
    {
        $factoryMock = $this->tester->mockFactoryMethod('getConfig', $this->createConfigMock($configurationFilename));
        $factoryMock = $this->tester->mockFactoryMethod('getRepository', new DynamicEntityRepository());
        $factoryMock = $this->tester->mockFactoryMethod('getEntityManager', new DynamicEntityEntityManager());
        $factoryMock = $this->tester->mockFactoryMethod('createFieldMappingValidator', $this->createFieldMappingValidatorMock());

        $this->dynamicEntityFacade->setFactory($factoryMock);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\DynamicEntity\Business\Installer\Validator\FieldMappingValidatorInterface
     */
    protected function createFieldMappingValidatorMock(): FieldMappingValidatorInterface
    {
        $fieldMappingValidatorMock = $this->getMockBuilder(FieldMappingValidatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $fieldMappingValidatorMock
            ->method('validate')
            ->willReturnSelf();

        return $fieldMappingValidatorMock;
    }

    /**
     * @param string $configurationFilename
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\DynamicEntity\DynamicEntityConfig
     */
    protected function createConfigMock(string $configurationFilename): DynamicEntityConfig
    {
        $configMock = $this->getMockBuilder(
            DynamicEntityConfig::class,
        )->getMock();

        $configMock
            ->method('getInstallerConfigurationDataFilePath')
            ->willReturn(sprintf('%s%s', codecept_data_dir(), $configurationFilename));

        return $configMock;
    }
}
