<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\DynamicEntityBackendApi\Controller;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueFilterTransfer;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery;
use Spryker\Glue\DynamicEntityBackendApi\Controller\DynamicEntityBackendApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group DynamicEntityBackendApi
 * @group Controller
 * @group DynamicEntityBackendApiControllerTest
 * Add your own group annotations below this line
 */
class DynamicEntityBackendApiControllerTest extends Unit
{
    /**
     * @var string
     */
    protected const RESPONSE_CODE_ALIAS_IS_WRONG = '1312';

    /**
     * @var string
     */
    protected const RESPONSE_KEY_DATA = 'data';

    /**
     * @var string
     */
    protected const ERROR_REQUEST_BODY_CONFLICT = 'Modification of immutable field `table_alias` is prohibited';

    /**
     * @var string
     */
    protected const ERROR_INCOMPLETE_REQUEST = 'Incomplete Request - missing identifier';

    /**
     * @var string
     */
    protected const ERROR_ENTITY_DOES_NOT_EXIST = 'The entity could not be found in the database.';

    /**
     * @var string
     */
    protected const REQUIRED_FIELD_IS_MISSING = 'The required field must not be empty.';

    /**
     * @var string
     */
    protected const ERROR_INVALID_DATA_FORMAT = 'Invalid or missing data format. Please ensure that the data is provided in the correct format. Example request body: {\'data\':[{...},{...},..]}';

    /**
     * @var string
     */
    protected const ERROR_FAILED_TO_PERSIST = 'Failed to persist the data. Please verify the provided data and try again.';

    /**
     * @var \SprykerTest\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiTester
     */
    protected $tester;

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\Controller\DynamicEntityBackendApiController
     */
    protected $controller;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->controller = new DynamicEntityBackendApiController();
        $this->tester->setupStorageRedisConfig();
    }

    /**
     * @return void
     */
    public function testGetCollectionActionReturnsCollection(): void
    {
        //Arrange
        $this->createFooEntity($this->tester->buildDefinitionWithNonAutoIncrementedId());

        $glueRequestTransfer = $this->tester->haveGlueRequestTransfer();
        $glueRequestTransfer->addFilter(
            (new GlueFilterTransfer())
                ->setField($this->tester::TABLE_ALIAS_COLUMN)
                ->setValue($this->tester::FOO_TABLE_ALIAS),
        );

        //Act
        $glueResponseTransfer = $this->controller->getCollectionAction($glueRequestTransfer);

        //Assert
        $this->assertNotNull($glueResponseTransfer->getContent());
        $content = json_decode($glueResponseTransfer->getContentOrFail(), true);
        $this->assertEquals($this->tester::FOO_TABLE_ALIAS, $content[static::RESPONSE_KEY_DATA][0][$this->tester::TABLE_ALIAS_COLUMN]);
    }

    /**
     * @return void
     */
    public function testGetCollectionActionReturnsEmptyCollectionIfTableAliasIsNotValid(): void
    {
        //Arrange
        $this->createFooEntity($this->tester->buildDefinitionWithNonAutoIncrementedId());
        $glueRequestTransfer = $this->tester->haveGlueRequestTransfer($this->tester::BAR_TABLE_ALIAS);

        //Act
        $glueResponseTransfer = $this->controller->getCollectionAction($glueRequestTransfer);

        //Assert
        $contentData = json_decode($glueResponseTransfer->getContent(), true);
        $errors = $glueResponseTransfer->getErrors();
        $this->assertNull($contentData);
        $this->assertNotEmpty($errors);
        $this->assertSame($errors[0]->getCode(), static::RESPONSE_CODE_ALIAS_IS_WRONG);
    }

    /**
     * @return void
     */
    public function testGetActionReturnsEntityById(): void
    {
        //Arrange
        $this->createFooEntity($this->tester->buildDefinitionWithNonAutoIncrementedId());
        $glueRequestTransfer = $this->tester->haveGlueRequestTransfer();

        //Act
        $glueResponseTransfer = $this->controller->getAction($this->tester::FOO_TABLE_ALIAS, $glueRequestTransfer);

        //Assert
        $this->assertNotNull($glueResponseTransfer->getContent());
        $content = json_decode($glueResponseTransfer->getContentOrFail(), true);
        $this->assertEquals($this->tester::FOO_TABLE_ALIAS, $content[static::RESPONSE_KEY_DATA][0][$this->tester::TABLE_ALIAS_COLUMN]);
    }

    /**
     * @return void
     */
    public function testPostActionSavesAndReturnsCollection(): void
    {
        //Arrange
        $this->createFooEntity($this->tester->buildDefinitionWithNonAutoIncrementedId());
        $glueRequestTransfer = $this->tester->haveGlueRequestTransfer();

        $content = [
            $this->tester::KEY_DATA => [
                [
                    $this->tester::TABLE_ALIAS_COLUMN => $this->tester::BAR_TABLE_ALIAS,
                    $this->tester::TABLE_NAME_COLUMN => $this->tester::BAR_TABLE_NAME,
                    $this->tester::DEFINITION_COLUMN => '',
                ],
            ],
        ];
        $glueRequestTransfer->setContent(json_encode($content));

        //Act
        $glueResponseTransfer = $this->controller->postAction($glueRequestTransfer);

        //Assert
        $this->assertEmpty($glueResponseTransfer->getErrors());
        $this->assertNotNull($glueResponseTransfer->getContent());

        $content = json_decode($glueResponseTransfer->getContentOrFail(), true);
        $this->assertEquals($this->tester::BAR_TABLE_ALIAS, $content[static::RESPONSE_KEY_DATA][0][$this->tester::TABLE_ALIAS_COLUMN]);

        $barEntity = $this->findEntityByTableAlias($this->tester::BAR_TABLE_ALIAS);
        $this->assertNotNull($barEntity);
    }

    /**
     * @return void
     */
    public function testPostActionReturnsErrorIfContentIsNotProvided(): void
    {
        //Arrange
        $this->createFooEntity($this->tester->buildDefinitionWithNonAutoIncrementedId());
        $glueRequestTransfer = $this->tester->haveGlueRequestTransfer();

        //Act
        $glueResponseTransfer = $this->controller->postAction($glueRequestTransfer);

        //Assert
        $this->assertEquals(
            $glueResponseTransfer->getErrors()[0]->getMessage(),
            static::ERROR_INVALID_DATA_FORMAT,
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $glueResponseTransfer->getErrors()[0]->getStatus());
    }

    /**
     * @return void
     */
    public function testPostActionReturnsErrorIfNotCreatableFieldIsProvided(): void
    {
        //Arrange
        $this->createFooEntity($this->tester->buildDefinitionWithNonAutoIncrementedId(false));

        $content = [
            $this->tester::KEY_DATA => [
                [
                    $this->tester::TABLE_ALIAS_COLUMN => $this->tester::BAR_TABLE_ALIAS,
                    $this->tester::TABLE_NAME_COLUMN => $this->tester::BAR_TABLE_NAME,
                    $this->tester::DEFINITION_COLUMN => '',
                ],
            ],
        ];
        $glueRequestTransfer = $this->tester->haveGlueRequestTransfer();
        $glueRequestTransfer->setContent(json_encode($content));

        //Act
        $glueResponseTransfer = $this->controller->postAction($glueRequestTransfer);

        //Assert
        $this->assertEquals(
            $glueResponseTransfer->getErrors()[0]->getMessage(),
            static::ERROR_REQUEST_BODY_CONFLICT,
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $glueResponseTransfer->getErrors()[0]->getStatus());
    }

    /**
     * @return void
     */
    public function testPatchActionUpdatesCollection(): void
    {
        //Arrange
        $this->createFooEntity($this->tester->buildDefinitionWithNonAutoIncrementedId());
        $glueRequestTransfer = $this->tester->haveGlueRequestTransfer();

        $content = [
            $this->tester::KEY_DATA => [
                [
                    $this->tester::TABLE_ALIAS_COLUMN => $this->tester::FOO_TABLE_ALIAS,
                    $this->tester::DEFINITION_COLUMN => $this->tester::DEFINITION_UPDATED_VALUE,
                ],
            ],
        ];
        $glueRequestTransfer->setContent(json_encode($content));

        //Act
        $glueResponseTransfer = $this->controller->patchAction($glueRequestTransfer);

        //Assert
        $this->assertEmpty($glueResponseTransfer->getErrors());
        $this->assertNotNull($glueResponseTransfer->getContent());

        $fooEntity = $this->findEntityByTableAlias($this->tester::FOO_TABLE_ALIAS);
        $this->assertEquals($this->tester::DEFINITION_UPDATED_VALUE, $fooEntity->getDefinition());
    }

    /**
     * @return void
     */
    public function testPatchActionReturnsErrorForCollectionIfIdIsNotProvided(): void
    {
        //Arrange
        $this->createFooEntity($this->tester->buildDefinitionWithNonAutoIncrementedId());
        $glueRequestTransfer = $this->tester->haveGlueRequestTransfer();
        $glueRequestTransfer->setMethod(Request::METHOD_PATCH);

        $content = [
            $this->tester::KEY_DATA => [
                [
                    $this->tester::DEFINITION_COLUMN => $this->tester::DEFINITION_UPDATED_VALUE,
                ],
            ],
        ];
        $glueRequestTransfer->setContent(json_encode($content));

        //Act
        $glueResponseTransfer = $this->controller->patchAction($glueRequestTransfer);

        //Assert
        $this->assertEquals(
            $glueResponseTransfer->getErrors()[0]->getMessage(),
            static::ERROR_INCOMPLETE_REQUEST,
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $glueResponseTransfer->getErrors()[0]->getStatus());
    }

    /**
     * @return void
     */
    public function testPatchActionReturnsErrorForCollectionIfIdDoesNotExist(): void
    {
        //Arrange
        $this->createFooEntity($this->tester->buildDefinitionWithNonAutoIncrementedId());
        $glueRequestTransfer = $this->tester->haveGlueRequestTransfer();

        $content = [
            $this->tester::KEY_DATA => [
                [
                    $this->tester::TABLE_ALIAS_COLUMN => $this->tester::BAR_TABLE_ALIAS,
                    $this->tester::DEFINITION_COLUMN => $this->tester::DEFINITION_UPDATED_VALUE,
                ],
            ],
        ];
        $glueRequestTransfer->setContent(json_encode($content));

        //Act
        $glueResponseTransfer = $this->controller->patchAction($glueRequestTransfer);

        //Assert
        $this->assertEquals(
            $glueResponseTransfer->getErrors()[0]->getMessage(),
            static::ERROR_ENTITY_DOES_NOT_EXIST,
        );
        $this->assertEquals(Response::HTTP_NOT_FOUND, $glueResponseTransfer->getErrors()[0]->getStatus());
    }

    /**
     * @return void
     */
    public function testPatchActionUpdatesById(): void
    {
        //Arrange
        $this->createFooEntity($this->tester->buildDefinitionWithNonAutoIncrementedId());
        $glueRequestTransfer = $this->tester->haveGlueRequestTransfer();
        $glueRequestTransfer->getResource()->setId($this->tester::FOO_TABLE_ALIAS);

        $content = [
            $this->tester::KEY_DATA => [
                $this->tester::DEFINITION_COLUMN => $this->tester::DEFINITION_UPDATED_VALUE,
            ],
        ];
        $glueRequestTransfer->setContent(json_encode($content));

        //Act
        $glueResponseTransfer = $this->controller->patchAction($glueRequestTransfer);

        //Assert
        $this->assertEmpty($glueResponseTransfer->getErrors());
        $this->assertNotNull($glueResponseTransfer->getContent());

        $fooEntity = $this->findEntityByTableAlias($this->tester::FOO_TABLE_ALIAS);
        $this->assertEquals($this->tester::DEFINITION_UPDATED_VALUE, $fooEntity->getDefinition());
    }

    /**
     * @return void
     */
    public function testPatchActionDoesNotUpdateIfContentIsNotValid(): void
    {
        //Arrange
        $this->createFooEntity($this->tester->buildDefinitionWithNonAutoIncrementedId());
        $glueRequestTransfer = $this->tester->haveGlueRequestTransfer();
        $glueRequestTransfer->getResource()->setId($this->tester::FOO_TABLE_ALIAS);

        $content = [
            $this->tester::KEY_DATA => [
                [
                    $this->tester::DEFINITION_COLUMN => $this->tester::DEFINITION_UPDATED_VALUE,
                ],
            ],
        ];
        $glueRequestTransfer->setContent(json_encode($content));

        //Act
        $glueResponseTransfer = $this->controller->patchAction($glueRequestTransfer);

        //Assert
        $this->assertEquals(
            $glueResponseTransfer->getErrors()[0]->getMessage(),
            static::ERROR_INVALID_DATA_FORMAT,
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $glueResponseTransfer->getErrors()[0]->getStatus());
    }

    /**
     * @return void
     */
    public function testPatchActionByIdReturnsErrorIfContentIsNotValid(): void
    {
        //Arrange
        $this->createFooEntity($this->tester->buildDefinitionWithNonAutoIncrementedId(true, false, false));
        $glueRequestTransfer = $this->tester->haveGlueRequestTransfer();
        $glueRequestTransfer->getResource()->setId($this->tester::FOO_TABLE_ALIAS);

        $content = [
            $this->tester::KEY_DATA => [
                $this->tester::TABLE_ALIAS_COLUMN => $this->tester::FOO_TABLE_ALIAS,
                $this->tester::DEFINITION_COLUMN => $this->tester::DEFINITION_UPDATED_VALUE,
            ],
        ];
        $glueRequestTransfer->setContent(json_encode($content));

        //Act
        $glueResponseTransfer = $this->controller->patchAction($glueRequestTransfer);

        //Assert
        $this->assertEquals(
            $glueResponseTransfer->getErrors()[0]->getMessage(),
            static::ERROR_REQUEST_BODY_CONFLICT,
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $glueResponseTransfer->getErrors()[0]->getStatus());
    }

    /**
     * @return void
     */
    public function testPutActionUpdatesCollection(): void
    {
        //Arrange
        $dynamicEntityDefinition = $this->tester->buildDefinitionWithAutoIncrementedId();
        $this->createFooEntity($dynamicEntityDefinition);
        $originalFooEntity = $this->findEntityByDefinition($dynamicEntityDefinition);
        $glueRequestTransfer = $this->tester->haveGlueRequestTransfer();
        $glueRequestTransfer->getResource()->setMethod(Request::METHOD_PUT);

        $content = [
            $this->tester::KEY_DATA => [
                [
                    $this->tester::TABLE_ID_DYNAMIC_ENTITY_CONFIGURATION_COLUMN => $originalFooEntity->getIdDynamicEntityConfiguration(),
                    $this->tester::TABLE_ALIAS_COLUMN => $this->tester::FOO_TABLE_ALIAS,
                    $this->tester::TABLE_NAME_COLUMN => $this->tester::TABLE_NAME,
                    $this->tester::DEFINITION_COLUMN => $this->tester::DEFINITION_UPDATED_VALUE,
                ],
            ],
        ];
        $glueRequestTransfer->setContent(json_encode($content));

        //Act
        $glueResponseTransfer = $this->controller->putAction($glueRequestTransfer);

        //Assert
        $this->assertEmpty($glueResponseTransfer->getErrors());
        $this->assertNotNull($glueResponseTransfer->getContent());

        $updatedFooEntity = $this->findEntityByTableAlias($this->tester::FOO_TABLE_ALIAS);
        $this->assertEquals($this->tester::DEFINITION_UPDATED_VALUE, $updatedFooEntity->getDefinition());
    }

    /**
     * @return void
     */
    public function testPutActionCreatesCollectionIfIdIsNotFound(): void
    {
        //Arrange
        $this->createFooEntity($this->tester->buildDefinitionWithNonAutoIncrementedId());
        $glueRequestTransfer = $this->tester->haveGlueRequestTransfer();
        $glueRequestTransfer->getResource()->setMethod(Request::METHOD_PUT);

        $content = [
            $this->tester::KEY_DATA => [
                [
                    $this->tester::TABLE_ALIAS_COLUMN => $this->tester::BAR_TABLE_ALIAS,
                    $this->tester::TABLE_NAME_COLUMN => $this->tester::BAR_TABLE_NAME,
                    $this->tester::DEFINITION_COLUMN => $this->tester::DEFINITION_CREATED_VALUE,
                ],
            ],
        ];
        $glueRequestTransfer->setContent(json_encode($content));

        //Act
        $glueResponseTransfer = $this->controller->putAction($glueRequestTransfer);

        //Assert
        $this->assertEmpty($glueResponseTransfer->getErrors());
        $this->assertNotNull($glueResponseTransfer->getContent());

        $content = json_decode($glueResponseTransfer->getContent(), true);
        $barEntity = $this->findEntityByTableAlias($content[static::RESPONSE_KEY_DATA][0][$this->tester::TABLE_ALIAS_COLUMN]);
        $this->assertNotNull($barEntity);
        $this->assertEquals($this->tester::DEFINITION_CREATED_VALUE, $barEntity->getDefinition());
    }

    /**
     * @return void
     */
    public function testPutActionUpdatesById(): void
    {
        //Arrange
        $dynamicEntityDefinition = $this->tester->buildDefinitionWithAutoIncrementedId();
        $this->createFooEntity($dynamicEntityDefinition);
        $originalFooEntity = $this->findEntityByDefinition($dynamicEntityDefinition);
        $glueRequestTransfer = $this->tester->haveGlueRequestTransfer();
        $glueRequestTransfer->getResource()
            ->setMethod(Request::METHOD_PUT)
            ->setId($originalFooEntity->getIdDynamicEntityConfiguration());

        $content = [
            $this->tester::KEY_DATA => [
                $this->tester::TABLE_NAME_COLUMN => $this->tester::TABLE_NAME,
                $this->tester::TABLE_ALIAS_COLUMN => $this->tester::BAR_TABLE_ALIAS,
                $this->tester::DEFINITION_COLUMN => $this->tester::DEFINITION_UPDATED_VALUE,
            ],
        ];
        $glueRequestTransfer->setContent(json_encode($content));

        //Act
        $glueResponseTransfer = $this->controller->putAction($glueRequestTransfer);

        //Assert
        $this->assertEmpty($glueResponseTransfer->getErrors());
        $this->assertNotNull($glueResponseTransfer->getContent());

        $updatedFooEntity = $this->findEntityByTableAlias($this->tester::BAR_TABLE_ALIAS);
        $this->assertEquals($this->tester::DEFINITION_UPDATED_VALUE, $updatedFooEntity->getDefinition());
    }

    /**
     * @return void
     */
    public function testPutActionUpdateByIdFailesIfIdIsProvidedInRequestBody(): void
    {
        //Arrange
        $this->createFooEntity($this->tester->buildDefinitionWithNonAutoIncrementedId(true, false, false));
        $glueRequestTransfer = $this->tester->haveGlueRequestTransfer();
        $glueRequestTransfer->getResource()
            ->setMethod(Request::METHOD_PUT)
            ->setId($this->tester::FOO_TABLE_ALIAS);

        $content = [
            $this->tester::KEY_DATA => [
                $this->tester::TABLE_ALIAS_COLUMN => $this->tester::BAR_TABLE_ALIAS,
                $this->tester::TABLE_NAME_COLUMN => $this->tester::BAR_TABLE_NAME,
                $this->tester::DEFINITION_COLUMN => $this->tester::DEFINITION_UPDATED_VALUE,
            ],
        ];
        $glueRequestTransfer->setContent(json_encode($content));

        //Act
        $glueResponseTransfer = $this->controller->putAction($glueRequestTransfer);

        //Assert
        $this->assertEquals(
            $glueResponseTransfer->getErrors()[0]->getMessage(),
            static::ERROR_REQUEST_BODY_CONFLICT,
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $glueResponseTransfer->getErrors()[0]->getStatus());
    }

    /**
     * @return void
     */
    public function testPutActionCreatesById(): void
    {
        //Arrange
        $this->createFooEntity($this->tester->buildDefinitionWithNonAutoIncrementedId());
        $glueRequestTransfer = $this->tester->haveGlueRequestTransfer();
        $glueRequestTransfer->getResource()
            ->setMethod(Request::METHOD_PUT)
            ->setId($this->tester::BAR_TABLE_ALIAS);

        $content = [
            $this->tester::KEY_DATA => [
                $this->tester::TABLE_NAME_COLUMN => $this->tester::BAR_TABLE_NAME,
                $this->tester::TABLE_ALIAS_COLUMN => $this->tester::BAR_TABLE_ALIAS,
                $this->tester::DEFINITION_COLUMN => $this->tester::DEFINITION_CREATED_VALUE,
            ],
        ];
        $glueRequestTransfer->setContent(json_encode($content));

        //Act
        $glueResponseTransfer = $this->controller->putAction($glueRequestTransfer);

        //Assert
        $this->assertEmpty($glueResponseTransfer->getErrors());
        $this->assertNotNull($glueResponseTransfer->getContent());

        $barEntity = $this->findEntityByTableAlias($this->tester::BAR_TABLE_ALIAS);
        $this->assertEquals($this->tester::DEFINITION_CREATED_VALUE, $barEntity->getDefinition());
    }

    /**
     * @return void
     */
    public function testPutActionCreateByIdFailesIfIdIsProvidedInRequestBody(): void
    {
        //Arrange
        $this->createFooEntity($this->tester->buildDefinitionWithNonAutoIncrementedId(false));
        $glueRequestTransfer = $this->tester->haveGlueRequestTransfer();
        $glueRequestTransfer->getResource()
            ->setMethod(Request::METHOD_PUT)
            ->setId($this->tester::BAR_TABLE_ALIAS);

        $content = [
            $this->tester::KEY_DATA => [
                $this->tester::TABLE_ALIAS_COLUMN => $this->tester::BAR_TABLE_ALIAS,
                $this->tester::TABLE_NAME_COLUMN => $this->tester::BAR_TABLE_NAME,
                $this->tester::DEFINITION_COLUMN => $this->tester::DEFINITION_CREATED_VALUE,
            ],
        ];
        $glueRequestTransfer->setContent(json_encode($content));

        //Act
        $glueResponseTransfer = $this->controller->putAction($glueRequestTransfer);

        //Assert
        $this->assertEquals(
            $glueResponseTransfer->getErrors()[0]->getMessage(),
            static::ERROR_REQUEST_BODY_CONFLICT,
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $glueResponseTransfer->getErrors()[0]->getStatus());
    }

    /**
     * @return void
     */
    public function testPutActionCreateByIdFailesIfIdIsNotCreatable(): void
    {
        //Arrange
        $this->createFooEntity($this->tester->buildDefinitionWithNonAutoIncrementedId(false));
        $glueRequestTransfer = $this->tester->haveGlueRequestTransfer();
        $glueRequestTransfer->getResource()
            ->setMethod(Request::METHOD_PUT)
            ->setId($this->tester::BAR_TABLE_ALIAS);

        $content = [
            $this->tester::KEY_DATA => [
                $this->tester::TABLE_ALIAS_COLUMN => $this->tester::BAR_TABLE_ALIAS,
                $this->tester::TABLE_NAME_COLUMN => $this->tester::BAR_TABLE_NAME,
                $this->tester::DEFINITION_COLUMN => $this->tester::DEFINITION_CREATED_VALUE,
            ],
        ];
        $glueRequestTransfer->setContent(json_encode($content));

        //Act
        $glueResponseTransfer = $this->controller->putAction($glueRequestTransfer);

        //Assert
        $this->assertEquals(
            $glueResponseTransfer->getErrors()[0]->getMessage(),
            static::ERROR_REQUEST_BODY_CONFLICT,
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $glueResponseTransfer->getErrors()[0]->getStatus());
    }

    /**
     * @param string|null $definition
     *
     * @return void
     */
    protected function createFooEntity(?string $definition = null): void
    {
        (new SpyDynamicEntityConfiguration())
            ->setIsActive(true)
            ->setTableAlias($this->tester::FOO_TABLE_ALIAS)
            ->setTableName($this->tester::TABLE_NAME)
            ->setDefinition($definition)
            ->save();
    }

    /**
     * @param string $tableAlias
     *
     * @return \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration|null
     */
    protected function findEntityByTableAlias(string $tableAlias): ?SpyDynamicEntityConfiguration
    {
        return SpyDynamicEntityConfigurationQuery::create()
            ->filterByTableAlias($tableAlias)
            ->findOne();
    }

    /**
     * @param string $definition
     *
     * @return \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration|null
     */
    protected function findEntityByDefinition(string $definition): ?SpyDynamicEntityConfiguration
    {
        return SpyDynamicEntityConfigurationQuery::create()
            ->filterByDefinition($definition)
            ->findOne();
    }
}
