<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oauth\Business\Model\League\Repositories;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Spryker\Zed\Oauth\Business\Model\League\Entities\ClientEntity;
use Spryker\Zed\Oauth\Business\Model\League\Entities\ScopeEntity;
use Spryker\Zed\Oauth\Business\Model\League\Repositories\ScopeRepository;
use Spryker\Zed\Oauth\Persistence\OauthRepository;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthScopeProviderPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oauth
 * @group Business
 * @group Model
 * @group League
 * @group Repositories
 * @group ScopeRepositoryTest
 * Add your own group annotations below this line
 */
class ScopeRepositoryTest extends Unit
{
    /**
     * @var string
     */
    protected const GRANT_TYPE = 'password';

    /**
     * @var string
     */
    protected const APPLICATION_NAME = 'GLUE_BACKEND_API_APPLICATION';

    /**
     * @var string
     */
    protected const SCOPE_FIRST_NAME = 'test:scope:first';

    /**
     * @var string
     */
    protected const SCOPE_SECOND_NAME = 'test:scope:second';

    /**
     * @var string
     */
    protected const CLIENT_ENTITY_IDENTIFIER = 'frontend';

    /**
     * @var string
     */
    protected const CLIENT_ENTITY_NAME = 'Customer client';

    /**
     * @return void
     */
    public function testFinalizeScopesWithDefaultScopesInOauthScopeProviderPlugins(): void
    {
        //Arrange
        $scopeRepository = new ScopeRepository(new OauthRepository(), [$this->createOauthScopeProviderPluginMock(true)], []);

        //Act
        $scopeEntities = $scopeRepository->finalizeScopes(
            [$this->createScopeEntity(static::SCOPE_FIRST_NAME)],
            static::GRANT_TYPE,
            $this->createClientEntity(
                static::CLIENT_ENTITY_IDENTIFIER,
                static::CLIENT_ENTITY_NAME,
            ),
            null,
            static::APPLICATION_NAME,
        );

        //Assert
        $this->assertIsArray($scopeEntities);
        $this->assertArrayHasKey(static::SCOPE_FIRST_NAME, $scopeEntities);
        $this->assertArrayHasKey(static::SCOPE_SECOND_NAME, $scopeEntities);
        $this->assertInstanceOf(ScopeEntity::class, $scopeEntities[static::SCOPE_FIRST_NAME]);
        $this->assertInstanceOf(ScopeEntity::class, $scopeEntities[static::SCOPE_SECOND_NAME]);
        $this->assertSame(static::SCOPE_FIRST_NAME, $scopeEntities[static::SCOPE_FIRST_NAME]->getIdentifier());
        $this->assertSame(static::SCOPE_SECOND_NAME, $scopeEntities[static::SCOPE_SECOND_NAME]->getIdentifier());
    }

    /**
     * @return void
     */
    public function testFinalizeScopesWithoutDefaultScopesInOauthScopeProviderPlugins(): void
    {
        //Arrange
        $scopeRepository = new ScopeRepository(new OauthRepository(), [$this->createOauthScopeProviderPluginMock(false)], []);

        //Act
        $scopeEntities = $scopeRepository->finalizeScopes(
            [$this->createScopeEntity(static::SCOPE_FIRST_NAME)],
            static::GRANT_TYPE,
            $this->createClientEntity(
                static::CLIENT_ENTITY_IDENTIFIER,
                static::CLIENT_ENTITY_NAME,
            ),
            null,
            static::APPLICATION_NAME,
        );

        //Assert
        $this->assertIsArray($scopeEntities);
        $this->assertArrayHasKey(static::SCOPE_FIRST_NAME, $scopeEntities);
        $this->assertArrayNotHasKey(static::SCOPE_SECOND_NAME, $scopeEntities);
        $this->assertInstanceOf(ScopeEntity::class, $scopeEntities[static::SCOPE_FIRST_NAME]);
        $this->assertSame(static::SCOPE_FIRST_NAME, $scopeEntities[static::SCOPE_FIRST_NAME]->getIdentifier());
    }

    /**
     * @param bool $isSuccess
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\OauthExtension\Dependency\Plugin\OauthScopeProviderPluginInterface
     */
    protected function createOauthScopeProviderPluginMock(bool $isSuccess): OauthScopeProviderPluginInterface
    {
        $oauthScopeProviderPluginMock = $this->createMock(OauthScopeProviderPluginInterface::class);

        $oauthScopeProviderPluginMock->method('accept')
            ->willReturn(true);

        $oauthScopeProviderPluginMock->method('getScopes')->willReturnCallback(
            function () use ($isSuccess) {
                $scopes = [];
                if ($isSuccess) {
                    $scopes[] = $this->createOauthScopeTransfer(static::SCOPE_FIRST_NAME);
                    $scopes[] = $this->createOauthScopeTransfer(static::SCOPE_SECOND_NAME);
                }

                return $scopes;
            },
        );

        return $oauthScopeProviderPluginMock;
    }

    /**
     * @param string $scope
     *
     * @return \Spryker\Zed\Oauth\Business\Model\League\Entities\ScopeEntity
     */
    protected function createScopeEntity(string $scope): ScopeEntity
    {
        $scopeEntity = new ScopeEntity();
        $scopeEntity->setIdentifier($scope);

        return $scopeEntity;
    }

    /**
     * @param string $identifier
     * @param string $name
     *
     * @return \Spryker\Zed\Oauth\Business\Model\League\Entities\ClientEntity
     */
    protected function createClientEntity(string $identifier, string $name): ClientEntity
    {
        $clientEntity = new ClientEntity();
        $clientEntity->setIdentifier($identifier);
        $clientEntity->setName($name);

        return $clientEntity;
    }

    /**
     * @param string $identifier
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer
     */
    protected function createOauthScopeTransfer(string $identifier): OauthScopeTransfer
    {
        $oauthScopeTransfer = new OauthScopeTransfer();

        return $oauthScopeTransfer->setIdentifier($identifier);
    }
}
