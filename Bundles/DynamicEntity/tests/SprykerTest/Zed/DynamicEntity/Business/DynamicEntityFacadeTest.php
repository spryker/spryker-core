<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DynamicEntity\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConditionsTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationConditionsTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldConditionTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery;
use Spryker\Zed\DynamicEntity\Business\DynamicEntityBusinessFactory;
use Spryker\Zed\DynamicEntity\Business\DynamicEntityFacade;
use Spryker\Zed\DynamicEntity\Business\DynamicEntityFacadeInterface;
use Spryker\Zed\DynamicEntity\DynamicEntityConfig;

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
    protected const TABLE_NAME = 'spy_dynamic_entity_configuration';

    /**
     * @var string
     */
    protected const FOO_TABLE_ALIAS_1 = 'FOO';

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
    protected const FOO_DEFINITION = '{"identifier":"id_dynamic_entity_configuration","fields":[{"fieldName":"id_dynamic_entity_configuration","fieldVisibleName":"id_dynamic_entity_configuration","isEditable":true,"isCreatable":false,"type":"integer","validation":{"isRequired":false}},{"fieldName":"table_alias","fieldVisibleName":"table_alias","type":"string","isEditable":true,"isCreatable":true,"validation":{"isRequired":false}},{"fieldName":"table_name","fieldVisibleName":"table_name","type":"string","isEditable":true,"isCreatable":true,"validation":{"isRequired":false}},{"fieldName":"definition","fieldVisibleName":"definition","type":"string","isEditable":true,"isCreatable":true,"validation":{"isRequired":false}}]}';

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

        $this->dynamicEntityFacade = $this->createDynamicEntityFacade();
        $this->createFooEntity();
    }

    /**
     * @return void
     */
    public function testGetDynamicEntityCollectionReturnsNotEmptyCollection(): void
    {
        //Arrange
        $dynamicEntityCriteriaTransfer = $this->haveDynamicEntityCriteriaTransfer(static::FOO_TABLE_ALIAS_1);

        //Act
        $dynamicEntityCollectionTransfer = $this->dynamicEntityFacade->getDynamicEntityCollection($dynamicEntityCriteriaTransfer);

        //Assert
        $totalEntitesCount = SpyDynamicEntityConfigurationQuery::create()->find()->count();
        $this->assertCount($totalEntitesCount, $dynamicEntityCollectionTransfer->getDynamicEntities());
        $this->assertEquals(static::FOO_TABLE_ALIAS_1, $dynamicEntityCollectionTransfer->getDynamicEntities()[$totalEntitesCount - 1]->getFields()['table_alias']);
        $this->assertNotContains('dynamicEntityDefinition', $dynamicEntityCollectionTransfer->getDynamicEntities()[$totalEntitesCount - 1]->getFields());
    }

    /**
     * @return void
     */
    public function testGetDynamicEntityCollectionReturnsFilteredCollection(): void
    {
        //Arrange
        $dynamicEntityCriteriaTransfer = $this->haveDynamicEntityCriteriaTransfer(static::FOO_TABLE_ALIAS_1, static::FOO_TABLE_ALIAS_1);

        //Act
        $dynamicEntityCollectionTransfer = $this->dynamicEntityFacade->getDynamicEntityCollection($dynamicEntityCriteriaTransfer);

        //Assert
        $this->assertCount(1, $dynamicEntityCollectionTransfer->getDynamicEntities());
        $this->assertEquals(static::FOO_TABLE_ALIAS_1, $dynamicEntityCollectionTransfer->getDynamicEntities()[0]->getFields()['table_alias']);
        $this->assertNotContains('dynamicEntityDefinition', $dynamicEntityCollectionTransfer->getDynamicEntities()[0]->getFields());
    }

    /**
     * @return void
     */
    public function testGetDynamicEntityCollectionReturnsEmptyCollection(): void
    {
        //Arrange
        $dynamicEntityCriteriaTransfer = $this->haveDynamicEntityCriteriaTransfer(static::FOO_TABLE_ALIAS_1, static::FOO_CONDITION);

        //Act
        $dynamicEntityCollectionTransfer = $this->dynamicEntityFacade->getDynamicEntityCollection($dynamicEntityCriteriaTransfer);

        //Assert
        $this->assertEmpty($dynamicEntityCollectionTransfer->getDynamicEntities());
    }

    /**
     * @return void
     */
    public function testCreateDynamicEntityCollectionCreatesTheRecord(): void
    {
        //Arrange
        $dynamicEntityCollectionRequestTransfer = $this->createDynamicEntityCollectionRequestTransfer();
        $dynamicEntityCollectionRequestTransfer->addDynamicEntity(
            (new DynamicEntityTransfer())
                ->setFields([
                    'table_alias' => static::FOO_TABLE_ALIAS_2,
                    'table_name' => static::FOO_TABLE_NAME,
                    'is_active' => 1,
                    'definition' => static::FOO_DEFINITION,
                ]),
        );

        //Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->createDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);

        //Assert
        $this->assertEmpty($dynamicEntityCollectionResponseTransfer->getErrors());

        $fooEntity = SpyDynamicEntityConfigurationQuery::create()
            ->filterByTableName(static::FOO_TABLE_NAME)
            ->find()
            ->getData();
        $this->assertEquals(static::FOO_TABLE_ALIAS_2, $fooEntity[0]->getTableAlias());
    }

    /**
     * @return void
     */
    public function testCreateDynamicEntityCollectionCreatesTheRecordWithDefaultFieldValueIfItIsNotPassed(): void
    {
        //Arrange
        $tableAliasUniq = uniqid();
        $tableNameUniq = 'spy_' . $tableAliasUniq;
        $dynamicEntityCollectionRequestTransfer = $this->createDynamicEntityCollectionRequestTransfer();
        $dynamicEntityCollectionRequestTransfer->addDynamicEntity(
            (new DynamicEntityTransfer())
                ->setFields([
                    'table_alias' => $tableAliasUniq,
                    'table_name' => $tableNameUniq,
                    'definition' => static::FOO_DEFINITION,
                ]),
        );

        //Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->createDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);

        //Assert
        $fooEntity = SpyDynamicEntityConfigurationQuery::create()
            ->filterByTableName($tableNameUniq)
            ->find()
            ->getData();
        $this->assertEquals(false, $fooEntity[0]->getIsActive());
    }

    /**
     * @return void
     */
    public function testCreateDynamicEntityCollectionReturnErrorIfInvalidDataTypeIsPassed(): void
    {
        //Arrange
        $dynamicEntityCollectionRequestTransfer = $this->createDynamicEntityCollectionRequestTransfer();
        $dynamicEntityCollectionRequestTransfer->addDynamicEntity(
            (new DynamicEntityTransfer())
                ->setFields([
                    'table_alias' => static::FOO_TABLE_ALIAS_1,
                    'table_name' => true,
                    'is_active' => 1,
                    'definition' => static::FOO_DEFINITION,
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
    public function testUpdateDynamicEntityCollectionUpdatesTheRecord(): void
    {
        //Arrange
        $fooEntity = SpyDynamicEntityConfigurationQuery::create()
            ->filterByTableAlias(static::FOO_TABLE_ALIAS_1)
            ->find()
            ->getData()[0];

        $dynamicEntityCollectionRequestTransfer = $this->createDynamicEntityCollectionRequestTransfer();
        $dynamicEntityCollectionRequestTransfer->addDynamicEntity(
            (new DynamicEntityTransfer())
                ->setFields([
                    'id_dynamic_entity_configuration' => $fooEntity->getIdDynamicEntityConfiguration(),
                    'table_name' => static::FOO_TABLE_NAME,
                ]),
        );

        //Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->updateDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);

        //Assert
        $updatedFooEntity = SpyDynamicEntityConfigurationQuery::create()
            ->filterByIdDynamicEntityConfiguration($fooEntity->getIdDynamicEntityConfiguration())
            ->find()
            ->getData()[0];
        $this->assertEmpty($dynamicEntityCollectionResponseTransfer->getErrors());
        $this->assertNotEquals($fooEntity->getTableName(), $updatedFooEntity->getTableName());
        $this->assertEquals(static::FOO_TABLE_NAME, $updatedFooEntity->getTableName());
    }

    /**
     * @return void
     */
    public function testUpdateDynamicEntityCollectionReturnsErrorIfInvalidDataTypeIsPassed(): void
    {
        //Arrange
        $fooEntity = SpyDynamicEntityConfigurationQuery::create()
            ->filterByTableAlias(static::FOO_TABLE_ALIAS_1)
            ->find()
            ->getData()[0];

        $dynamicEntityCollectionRequestTransfer = $this->createDynamicEntityCollectionRequestTransfer();
        $dynamicEntityCollectionRequestTransfer->addDynamicEntity(
            (new DynamicEntityTransfer())
                ->setFields([
                    'id_dynamic_entity_configuration' => $fooEntity->getIdDynamicEntityConfiguration(),
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
    public function testUpdateDynamicEntityCollectionDoesNotUpdateIfFieldIsNotEditable(): void
    {
        //Arrange
        $fooEntity = SpyDynamicEntityConfigurationQuery::create()
            ->filterByTableAlias(static::FOO_TABLE_ALIAS_1)
            ->find()
            ->getData()[0];

        $dynamicEntityCollectionRequestTransfer = $this->createDynamicEntityCollectionRequestTransfer();
        $dynamicEntityCollectionRequestTransfer->addDynamicEntity(
            (new DynamicEntityTransfer())
                ->setFields([
                    'id_dynamic_entity_configuration' => $fooEntity->getIdDynamicEntityConfiguration(),
                    'isActive' => false,
                ]),
        );

        //Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->updateDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);

        //Assert
        $updatedFooEntity = SpyDynamicEntityConfigurationQuery::create()
            ->filterByIdDynamicEntityConfiguration($fooEntity->getIdDynamicEntityConfiguration())
            ->find()
            ->getData()[0];

        $this->assertEmpty($dynamicEntityCollectionResponseTransfer->getErrors());
        $this->assertTrue($updatedFooEntity->getIsActive());
        $this->assertEquals($fooEntity->getIsActive(), $updatedFooEntity->getIsActive());
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
        $this->assertEquals(static::FOO_TABLE_ALIAS_1, $dynamicEntityConfigurationTransfer->getTableAlias());
    }

    /**
     * @return void
     */
    public function testInstallNotFails(): void
    {
        // Arrange
        $configMock = $this->getMockBuilder(
            DynamicEntityConfig::class,
        )->getMock();

        $configMock
            ->method('getInstallerConfigurationDataFilePath')
            ->willReturn(sprintf('%sconfiguration.json', codecept_data_dir()));

        $factory = new DynamicEntityBusinessFactory();
        $factory->setConfig($configMock);
        $this->dynamicEntityFacade->setFactory($factory);

        // Act
        $this->dynamicEntityFacade->install();

        // Assert
        $this->assertCount(
            1,
            SpyDynamicEntityConfigurationQuery::create()
                ->filterByTableAlias('test')
                ->find(),
        );
        $this->assertCount(
            0,
            SpyDynamicEntityConfigurationQuery::create()
                ->filterByTableAlias('tests')
                ->find(),
        );
    }

    /**
     * @return void
     */
    public function testCreateDynamicEntityCollectionCreatesTheRecordAndReturnsValidReponseTransfer(): void
    {
        //Arrange
        $resourceName = 'resource-1';
        $tableName = 'spy_resource_1';
        $dynamicEntityCollectionRequestTransfer = $this->createDynamicEntityCollectionRequestTransfer();
        $dynamicEntityCollectionRequestTransfer->addDynamicEntity(
            (new DynamicEntityTransfer())
                ->setFields([
                    'table_alias' => $resourceName,
                    'table_name' => $tableName,
                    'definition' => '{}',
                ]),
        );

        //Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->createDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);

        //Assert
        $this->assertEmpty($dynamicEntityCollectionResponseTransfer->getErrors());

        $spyDynamicEntityConfiguratioEntity = SpyDynamicEntityConfigurationQuery::create()
            ->filterByTableName($tableName)
            ->find()
            ->getData();
        $this->assertEquals($resourceName, $spyDynamicEntityConfiguratioEntity[0]->getTableAlias());
        $this->assertIsNumeric($dynamicEntityCollectionResponseTransfer->getDynamicEntities()[0]->getFields()['id_dynamic_entity_configuration']);
        $this->assertEquals($spyDynamicEntityConfiguratioEntity[0]->getTableAlias(), $dynamicEntityCollectionResponseTransfer->getDynamicEntities()[0]->getFields()['table_alias']);
        $this->assertEquals($spyDynamicEntityConfiguratioEntity[0]->getTableName(), $dynamicEntityCollectionResponseTransfer->getDynamicEntities()[0]->getFields()['table_name']);
        $this->assertEquals($spyDynamicEntityConfiguratioEntity[0]->getDefinition(), $dynamicEntityCollectionResponseTransfer->getDynamicEntities()[0]->getFields()['definition']);
    }

    /**
     * @return void
     */
    public function testUpdateDynamicEntityCollectionUpdatesTheRecordAndReturnsCorrectResponseTransfer(): void
    {
        //Arrange
        $fooEntity = SpyDynamicEntityConfigurationQuery::create()
            ->filterByTableAlias(static::FOO_TABLE_ALIAS_1)
            ->find()
            ->getData()[0];

        $dynamicEntityCollectionRequestTransfer = $this->createDynamicEntityCollectionRequestTransfer();
        $dynamicEntityCollectionRequestTransfer->addDynamicEntity(
            (new DynamicEntityTransfer())
                ->setFields([
                    'id_dynamic_entity_configuration' => $fooEntity->getIdDynamicEntityConfiguration(),
                    'table_name' => static::FOO_TABLE_NAME,
                ]),
        );

        //Act
        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityFacade->updateDynamicEntityCollection($dynamicEntityCollectionRequestTransfer);

        //Assert
        $updatedFooEntity = SpyDynamicEntityConfigurationQuery::create()
            ->filterByIdDynamicEntityConfiguration($fooEntity->getIdDynamicEntityConfiguration())
            ->find()
            ->getData()[0];
        $this->assertEmpty($dynamicEntityCollectionResponseTransfer->getErrors());
        $this->assertNotEquals($fooEntity->getTableName(), $updatedFooEntity->getTableName());
        $this->assertEquals(static::FOO_TABLE_NAME, $updatedFooEntity->getTableName());
        $this->assertEquals($updatedFooEntity->getTableName(), $dynamicEntityCollectionResponseTransfer->getDynamicEntities()[0]->getFields()['table_name']);
        $this->assertEquals($updatedFooEntity->getTableAlias(), $dynamicEntityCollectionResponseTransfer->getDynamicEntities()[0]->getFields()['table_alias']);
        $this->assertEquals($updatedFooEntity->getDefinition(), $dynamicEntityCollectionResponseTransfer->getDynamicEntities()[0]->getFields()['definition']);
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
     * @return \Spryker\Zed\DynamicEntity\Business\DynamicEntityFacadeInterface
     */
    protected function createDynamicEntityFacade(): DynamicEntityFacadeInterface
    {
        return new DynamicEntityFacade();
    }

    /**
     * @return void
     */
    protected function createFooEntity(): void
    {
        (new SpyDynamicEntityConfiguration())
            ->setIsActive(true)
            ->setTableAlias(static::FOO_TABLE_ALIAS_1)
            ->setTableName(static::TABLE_NAME)
            ->setDefinition(static::FOO_DEFINITION)
            ->save();
    }

    /**
     * @param string $tableAlias
     * @param string|null $filterCondition
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer
     */
    protected function haveDynamicEntityCriteriaTransfer(
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
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer
     */
    protected function createDynamicEntityCollectionRequestTransfer(): DynamicEntityCollectionRequestTransfer
    {
        $dynamicEntityCollectionRequestTransfer = new DynamicEntityCollectionRequestTransfer();
        $dynamicEntityCollectionRequestTransfer->setTableAlias(static::FOO_TABLE_ALIAS_1);

        return $dynamicEntityCollectionRequestTransfer;
    }
}
