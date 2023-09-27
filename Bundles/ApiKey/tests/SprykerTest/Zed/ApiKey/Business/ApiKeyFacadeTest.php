<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ApiKey\Business;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\ApiKeyCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\ApiKeyCollectionRequestTransfer;
use Generated\Shared\Transfer\ApiKeyConditionsTransfer;
use Generated\Shared\Transfer\ApiKeyCriteriaTransfer;
use Generated\Shared\Transfer\ApiKeyTransfer;
use Generated\Shared\Transfer\CriteriaRangeFilterTransfer;
use Spryker\Zed\ApiKey\ApiKeyDependencyProvider;
use Spryker\Zed\ApiKey\Dependency\Facade\ApiKeyToUserFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ApiKey
 * @group Business
 * @group Facade
 * @group ApiKeyFacadeTest
 * Add your own group annotations below this line
 */
class ApiKeyFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const INVALID_NAME = '1000!Foo';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_INVALID_NAME_FORMAT = 'The provided key name `%s` is not in valid format. It should only contain letters (a-z) and digits (0-9). Example: `YourExample123`.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_DUPLICATED_NAME = 'The provided key name `%s` is duplicated. Use another one and try again.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_INVALID_VALID_TO_DATE = 'The provided `valid to` date `%s` already expired. Use another one and try again.';

    /**
     * @var string
     */
    protected const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var \SprykerTest\Zed\ApiKey\ApiKeyBusinessTester
     */
    protected $tester;

    /**
     * @var int
     */
    protected $apiKeyEntity;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\User\Business\UserFacadeInterface
     */
    protected $userFacadeMock;

    /**
     * @var \Generated\Shared\Transfer\UserTransfer
     */
    protected $userTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->createFakeApiKeyRecord();
        $this->tester->createFakeApiKeyNotExpiredRecord();
        $this->tester->createFakeApiKeyExpiredRecord();
        $this->apiKeyEntity = $this->tester->getFakeApiKeyEntityByName($this->tester::FOO_NAME);
        $this->userTransfer = $this->tester->haveUser();
        $this->mockUserFacade();
    }

    /**
     * @return void
     */
    public function testGetApiKeyCollectionReturnsCollectionResponseTransferWithValidData(): void
    {
        //Arrange
        $apiKeyCriteriaTransfer = (new ApiKeyCriteriaTransfer())
            ->setApiKeyConditions((new ApiKeyConditionsTransfer())
                ->addIdApiKey($this->apiKeyEntity->getIdApiKey()));

        //Act
        $apiKeyCollectionTransfer = $this->tester->getFacade()->getApiKeyCollection($apiKeyCriteriaTransfer);

        //Assert
        $this->assertEquals(1, $apiKeyCollectionTransfer->getApiKeys()->count());
        $this->assertEquals($this->apiKeyEntity->getIdApiKey(), $apiKeyCollectionTransfer->getApiKeys()->offsetGet(0)->getIdApiKeyOrFail());
        $this->assertEquals($this->tester::FOO_NAME, $apiKeyCollectionTransfer->getApiKeys()->offsetGet(0)->getNameOrFail());
        $this->assertNull($apiKeyCollectionTransfer->getApiKeys()->offsetGet(0)->getKey());
    }

    /**
     * @return void
     */
    public function testGetApiKeyCollectionReturnsCollectionResponseTransferWithNoExpiredValidData(): void
    {
        //Arrange
        $apiKeyCriteriaTransfer = (new ApiKeyCriteriaTransfer())
            ->setApiKeyConditions((new ApiKeyConditionsTransfer())
                ->addApiKey($this->tester::NO_EXPIRED_API_KEY)
                ->setFilterValidTo(
                    (new CriteriaRangeFilterTransfer())
                        ->setFrom((new DateTime())->format(static::DATE_TIME_FORMAT)),
                ));

        //Act
        $apiKeyCollectionTransfer = $this->tester->getFacade()->getApiKeyCollection($apiKeyCriteriaTransfer);

        //Assert
        $this->assertEquals(1, $apiKeyCollectionTransfer->getApiKeys()->count());
        $this->assertEquals($this->tester::NO_EXPIRED_KEY_NAME, $apiKeyCollectionTransfer->getApiKeys()->offsetGet(0)->getName());
    }

    /**
     * @return void
     */
    public function testGetApiKeyCollectionReturnsCollectionResponseTransferWithoutTransfers(): void
    {
        //Arrange
        $apiKeyCriteriaTransfer = (new ApiKeyCriteriaTransfer())
            ->setApiKeyConditions((new ApiKeyConditionsTransfer())
                ->addApiKey($this->tester::EXPIRED_API_KEY)
                ->setFilterValidTo(
                    (new CriteriaRangeFilterTransfer())
                        ->setFrom((new DateTime())->format(static::DATE_TIME_FORMAT)),
                ));

        //Act
        $apiKeyCollectionTransfer = $this->tester->getFacade()->getApiKeyCollection($apiKeyCriteriaTransfer);

        //Assert
        $this->assertEquals(0, $apiKeyCollectionTransfer->getApiKeys()->count());
    }

    /**
     * @return void
     */
    public function testGetApiKeyCollectionReturnsEmptyCollectionResponseTransferIfIdApiKeyIsInvalid(): void
    {
        //Arrange
        $apiKeyCriteriaTransfer = (new ApiKeyCriteriaTransfer())
            ->setApiKeyConditions((new ApiKeyConditionsTransfer())
                ->addIdApiKey($this->tester->getNonExistingId()));

        //Act
        $apiKeyCollectionTransfer = $this->tester->getFacade()->getApiKeyCollection($apiKeyCriteriaTransfer);

        //Assert
        $this->assertEmpty($apiKeyCollectionTransfer->getApiKeys());
    }

    /**
     * @return void
     */
    public function testCreateApiKeyCollectionCreatesRecord(): void
    {
        //Arrange
        $apiKeyCollectionRequestTransfer = (new ApiKeyCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addApiKey((new ApiKeyTransfer())
                ->setName($this->tester::BAR_NAME)
                ->setKey($this->tester::FOO_KEY));

        //Act
        $apiKeyCollectionResponseTransfer = $this->tester->getFacade()->createApiKeyCollection($apiKeyCollectionRequestTransfer);

        //Assert
        $createdApiKeyEntity = $this->tester->getFakeApiKeyEntityByName($this->tester::BAR_NAME);
        $this->assertEquals($this->userTransfer->getIdUser(), $createdApiKeyEntity->getCreatedBy());
        $this->assertNotEmpty($createdApiKeyEntity->getKeyHash());
    }

    /**
     * @return void
     */
    public function testCreateApiKeyCollectionWithValidToDateToCreatesRecord(): void
    {
        //Arrange
        $apiKeyCollectionRequestTransfer = (new ApiKeyCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addApiKey((new ApiKeyTransfer())
                ->setName($this->tester::BAR_NAME)
                ->setKey($this->tester::FOO_KEY)
                ->setValidTo(date(static::DATE_TIME_FORMAT, strtotime('+1 day'))));

        //Act
        $apiKeyCollectionResponseTransfer = $this->tester->getFacade()->createApiKeyCollection($apiKeyCollectionRequestTransfer);

        //Assert
        $createdApiKeyEntity = $this->tester->getFakeApiKeyEntityByName($this->tester::BAR_NAME);
        $this->assertEquals($this->userTransfer->getIdUser(), $createdApiKeyEntity->getCreatedBy());
        $this->assertNotEmpty($createdApiKeyEntity->getKeyHash());
    }

    /**
     * @return void
     */
    public function testCreateApiKeyCollectionWithInvalidReturnsError(): void
    {
        //Arrange
        $validToDate = date(static::DATE_TIME_FORMAT, strtotime('-1 day'));
        $apiKeyCollectionRequestTransfer = (new ApiKeyCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addApiKey((new ApiKeyTransfer())
                ->setName($this->tester::BAR_NAME)
                ->setKey($this->tester::FOO_KEY)
                ->setValidTo(date(static::DATE_TIME_FORMAT, strtotime('-1 day'))));

        //Act
        $apiKeyCollectionResponseTransfer = $this->tester->getFacade()->createApiKeyCollection($apiKeyCollectionRequestTransfer);

        //Assert
        $this->assertEquals(
            sprintf(static::ERROR_MESSAGE_INVALID_VALID_TO_DATE, $validToDate),
            $apiKeyCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessageOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testCreateApiKeyCollectionReturnsErrorIfProvidedNameIsInvalid(): void
    {
        //Arrange
        $apiKeyCollectionRequestTransfer = (new ApiKeyCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addApiKey((new ApiKeyTransfer())
                ->setName(static::INVALID_NAME));

        //Act
        $apiKeyCollectionResponseTransfer = $this->tester->getFacade()->createApiKeyCollection($apiKeyCollectionRequestTransfer);

        //Assert
        $this->assertEquals(
            static::ERROR_MESSAGE_INVALID_NAME_FORMAT,
            $apiKeyCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessageOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testCreateApiKeyCollectionReturnsErrorIfProvidedNameIsDuplicated(): void
    {
        //Arrange
        $apiKeyCollectionRequestTransfer = (new ApiKeyCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addApiKey((new ApiKeyTransfer())
                ->setName($this->tester::FOO_NAME));

        //Act
        $apiKeyCollectionResponseTransfer = $this->tester->getFacade()->createApiKeyCollection($apiKeyCollectionRequestTransfer);

        //Assert
        $this->assertEquals(
            static::ERROR_MESSAGE_DUPLICATED_NAME,
            $apiKeyCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessageOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testUpdateApiKeyCollectionUpdatesOnlyNameIfKeyIsNotRequestedToBeRegenerated(): void
    {
        //Arrange
        $apiKeyCollectionRequestTransfer = (new ApiKeyCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addApiKey((new ApiKeyTransfer())
                ->setIdApiKey($this->apiKeyEntity->getIdApiKey())
                ->setName($this->tester::BAR_NAME));

        //Act
        $apiKeyCollectionResponseTransfer = $this->tester->getFacade()->updateApiKeyCollection($apiKeyCollectionRequestTransfer);

        //Assert
        $updatedApiKeyEntity = $this->tester->getFakeApiKeyEntityById($this->apiKeyEntity->getIdApiKey());
        $this->assertSame($this->apiKeyEntity->getKeyHash(), $updatedApiKeyEntity->getKeyHash());
        $this->assertSame($this->tester::BAR_NAME, $updatedApiKeyEntity->getName());
    }

    /**
     * @return void
     */
    public function testUpdateApiKeyCollectionUpdatesKeyHash(): void
    {
        //Arrange
        $apiKeyCollectionRequestTransfer = (new ApiKeyCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addApiKey((new ApiKeyTransfer())
                ->setIdApiKey($this->apiKeyEntity->getIdApiKey())
                ->setName($this->tester::FOO_NAME)
                ->setKey($this->tester::FOO_KEY));

        //Act
        $apiKeyCollectionResponseTransfer = $this->tester->getFacade()->updateApiKeyCollection($apiKeyCollectionRequestTransfer);

        //Assert
        $updatedApiKeyEntity = $this->tester->getFakeApiKeyEntityById($this->apiKeyEntity->getIdApiKey());
        $this->assertNotEquals($this->apiKeyEntity->getKeyHash(), $updatedApiKeyEntity->getKeyHash());
        $this->assertNotEmpty($updatedApiKeyEntity->getKeyHash());
    }

    /**
     * @return void
     */
    public function testDeleteApiKeyCollectionRemovesEntity(): void
    {
        //Arrange
        $apiKeyCollectionDeleteCriteriaTransfer = (new ApiKeyCollectionDeleteCriteriaTransfer())
                ->addIdApiKey($this->apiKeyEntity->getIdApiKey());

        //Act
        $apiKeyCollectionResponseTransfer = $this->tester->getFacade()->deleteApiKeyCollection($apiKeyCollectionDeleteCriteriaTransfer);

        //Assert
        $removedApiKeyEntity = $this->tester->getFakeApiKeyEntityById($this->apiKeyEntity->getIdApiKey());
        $this->assertNull($removedApiKeyEntity);
    }

    /**
     * @return void
     */
    protected function mockUserFacade(): void
    {
        $this->userFacadeMock = $this->getMockBuilder(ApiKeyToUserFacadeInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'getCurrentUser',
            ])
            ->getMockForAbstractClass();

        $this->tester->setDependency(
            ApiKeyDependencyProvider::FACADE_USER,
            $this->userFacadeMock,
        );

        $this->userFacadeMock->method('getCurrentUser')->willReturn($this->userTransfer);
    }
}
