<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Vault\Business;

use Codeception\Test\Unit;
use Orm\Zed\Vault\Persistence\SpyVaultQuery;
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
        $isSuccessful = $this->createVaultFacadeMock()->store(static::TEST_DATA_TYPE, static::TEST_DATA_KEY, static::TEST_DATA);

        //Assert
        $this->assertSame(true, $isSuccessful);

        $vaultsCount = SpyVaultQuery::create()
            ->filterByDataType(static::TEST_DATA_TYPE)
            ->filterByDataKey(static::TEST_DATA_KEY)
            ->find()
            ->count();

        $this->assertSame(1, $vaultsCount);
    }

    /**
     * @return void
     */
    public function testRetrieveRetrievesString(): void
    {
        //Arrange
        $facadeMock = $this->createVaultFacadeMock();
        $facadeMock->store(static::TEST_DATA_TYPE, static::TEST_DATA_KEY, static::TEST_DATA);

        //Act
        $string = $facadeMock->retrieve(static::TEST_DATA_TYPE, static::TEST_DATA_KEY);

        //Assert
        $this->assertSame(static::TEST_DATA, $string);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Vault\Business\VaultBusinessFactory
     */
    protected function createVaultFacadeMock()
    {
        $vaultConfigMock = $this->getMockBuilder(VaultConfig::class)
            ->setMethods(['getEncryptionKeyPerType'])
            ->getMock();

        $vaultConfigMock->method('getEncryptionKeyPerType')
            ->willReturn(static::TEST_ENCRYPTION_KEY);

        $vaultBusinessFactoryMock = $this->getMockBuilder(VaultBusinessFactory::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();

        $vaultBusinessFactoryMock->setConfig($vaultConfigMock);

        $vaultFacadeMock = $this->getMockBuilder(VaultFacade::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();

        $vaultFacadeMock->setFactory($vaultBusinessFactoryMock);

        return $vaultFacadeMock;
    }
}
