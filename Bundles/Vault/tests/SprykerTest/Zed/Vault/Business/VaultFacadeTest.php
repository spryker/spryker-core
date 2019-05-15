<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Vault\Business;

use Codeception\Test\Unit;
use Orm\Zed\Vault\Persistence\SpyVaultDepositQuery;
use Spryker\Shared\Vault\Exception\EncryptionKeyNotPreConfigured;
use Spryker\Shared\Vault\VaultConfig as VaultSharedConfig;
use Spryker\Shared\Vault\VaultConstants;
use Spryker\Zed\Vault\Business\VaultBusinessFactory;
use Spryker\Zed\Vault\Business\VaultFacade;
use Spryker\Zed\Vault\VaultConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Vault
 * @group Business
 * @group Facade
 * @group VaultFacadeTest
 * Add your own group annotations below this line
 */
class VaultFacadeTest extends Unit
{
    protected const TEST_DATA_TYPE = 'TEST_DATA_TYPE';
    protected const TEST_DATA_KEY = 'TEST_DATA_KEY';
    protected const TEST_DATA = 'TEST_DATA';
    protected const TEST_UPDATED_DATA = 'TEST_UPDATED_DATA';
    protected const TEST_ENCRYPTION_KEY = 'TEST_ENCRYPTION_KEY';

    /**
     * @var \SprykerTest\Zed\Vault\VaultBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testStoreStoresString(): void
    {
        //Act
        $isSuccessful = $this->createVaultFacadeMock(static::TEST_ENCRYPTION_KEY)->store(static::TEST_DATA_TYPE, static::TEST_DATA_KEY, static::TEST_DATA);

        //Assert
        $this->assertSame(true, $isSuccessful);

        $vaultDepositsCount = SpyVaultDepositQuery::create()
            ->filterByDataType(static::TEST_DATA_TYPE)
            ->filterByDataKey(static::TEST_DATA_KEY)
            ->find()
            ->count();

        $this->assertSame(1, $vaultDepositsCount);
    }

    /**
     * @return void
     */
    public function testRetrieveReturnsDecryptedStringIfExist(): void
    {
        //Arrange
        $vaultFacadeMock = $this->createVaultFacadeMock(static::TEST_ENCRYPTION_KEY);
        $vaultFacadeMock->store(static::TEST_DATA_TYPE, static::TEST_DATA_KEY, static::TEST_DATA);

        //Act
        $data = $vaultFacadeMock->retrieve(static::TEST_DATA_TYPE, static::TEST_DATA_KEY);

        //Assert
        $this->assertSame(static::TEST_DATA, $data);
    }

    /**
     * @return void
     */
    public function testRetrieveReturnsNullIfNothingFound(): void
    {
        //Act
        $data = $this->createVaultFacadeMock(static::TEST_ENCRYPTION_KEY)->retrieve(static::TEST_DATA_TYPE, static::TEST_DATA_KEY);

        //Assert
        $this->assertNull($data);
    }

    /**
     * @return void
     */
    public function testStoreThrowsExceptionIfEncryptionKeyNotPreConfigured(): void
    {
        //Arrange
        $vaultFacade = $this->createVaultFacadeMock(null);

        //Assert
        $this->expectException(EncryptionKeyNotPreConfigured::class);

        //Act
        $vaultFacade->store(static::TEST_DATA_TYPE, static::TEST_DATA_KEY, static::TEST_DATA);
    }

    /**
     * @return void
     */
    public function testStoreOverwritesVaultDepositIfExist(): void
    {
        //Arrange
        $vaultFacadeMock = $this->createVaultFacadeMock(static::TEST_ENCRYPTION_KEY);
        $vaultFacadeMock->store(static::TEST_DATA_TYPE, static::TEST_DATA_KEY, static::TEST_DATA);

        //Act
        $vaultFacadeMock->store(static::TEST_DATA_TYPE, static::TEST_DATA_KEY, static::TEST_UPDATED_DATA);

        //Assert
        $data = $vaultFacadeMock->retrieve(static::TEST_DATA_TYPE, static::TEST_DATA_KEY);

        $this->assertSame($data, static::TEST_UPDATED_DATA);
    }

    /**
     * @param string|null $encryptionKey
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Vault\Business\VaultFacadeInterface
     */
    protected function createVaultFacadeMock(?string $encryptionKey)
    {
        $vaultFacadeMock = $this->getMockBuilder(VaultFacade::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();

        $vaultFacadeMock->setFactory($this->createVaultBusinessFactoryMock($encryptionKey));

        return $vaultFacadeMock;
    }

    /**
     * @param string|null $encryptionKey
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Vault\Business\VaultBusinessFactory
     */
    protected function createVaultBusinessFactoryMock(?string $encryptionKey)
    {
        $vaultBusinessFactoryMock = $this->getMockBuilder(VaultBusinessFactory::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();

        $vaultBusinessFactoryMock->setConfig($this->createVaultConfigMock($encryptionKey));

        return $vaultBusinessFactoryMock;
    }

    /**
     * @param string|null $encryptionKey
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Vault\VaultConfig
     */
    protected function createVaultConfigMock(?string $encryptionKey)
    {
        $vaultConfigMock = $this->getMockBuilder(VaultConfig::class)
            ->setMethods(['getSharedConfig'])
            ->getMock();

        $vaultConfigMock->method('getSharedConfig')
            ->willReturn($this->createVaultSharedConfigMock($encryptionKey));

        return $vaultConfigMock;
    }

    /**
     * @param string|null $encryptionKey
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Vault\VaultConfig
     */
    protected function createVaultSharedConfigMock(?string $encryptionKey)
    {
        $vaultSharedConfigMock = $this->getMockBuilder(VaultSharedConfig::class)
            ->setMethods(['get'])
            ->getMock();

        $vaultSharedConfigMock->method('get')
            ->with(VaultConstants::ENCRYPTION_KEY, false)
            ->willReturn($encryptionKey);

        return $vaultSharedConfigMock;
    }
}
