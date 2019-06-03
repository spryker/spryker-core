<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthPermission\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;
use Spryker\Zed\Oauth\OauthConfig;
use Spryker\Zed\OauthPermission\Business\OauthPermissionBusinessFactory;
use Spryker\Zed\OauthPermission\Business\OauthPermissionFacadeInterface;
use Spryker\Zed\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group OauthPermission
 * @group Business
 * @group Facade
 * @group OauthPermissionFacadeTest
 * Add your own group annotations below this line
 */
class OauthPermissionFacadeTest extends Unit
{
    protected const PERMISSION_PLUGIN_KEY = 'TestPermissionPlugin';

    protected const KEY_PERMISSIONS = 'permissions';

    /**
     * @var \SprykerTest\Zed\OauthPermission\OauthPermissionBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
//    public function testExpandCustomerIdentifierWillExpandCustomerDataWithCorrectPermissionsData(): void
//    {
//        //Assign
//        $this->tester->preparePermissionStorageDependency($this->createPermissionStoragePluginStub());
//        $companyUserTransfer = $this->tester->haveCompanyUserWithPermissions($this->createPermissionPluginMock());
//        $customerIdentifierTransfer = (new CustomerIdentifierTransfer())
//            ->setIdCompanyUser($companyUserTransfer->getUuid());
//
//        //Act
//        $customerIdentifierTransfer = $this->getOauthPermissionFacade()->expandCustomerIdentifierWithPermissions(
//            $customerIdentifierTransfer,
//            $companyUserTransfer->getCustomer()
//        );
//
//        //Assert
//        $this->assertNotNull($customerIdentifierTransfer->getPermissions());
//        $this->assertCount(1, $customerIdentifierTransfer->getPermissions()->getPermissions());
//        $this->assertEquals(
//            static::PERMISSION_PLUGIN_KEY,
//            $customerIdentifierTransfer->getPermissions()->getPermissions()->offsetGet(0)->getKey()
//        );
//    }

    /**
     * @return void
     */
    public function testFilterOauthUserIdentifierWillFilterOutConfiguredKeys(): void
    {
        //Assign
        $userIdentifier = $this->getUserIdentifierArray();

        $configMock = $this->createMock(OauthConfig::class)
            ->method('getOauthUserIdentifierFilterKeys')
            ->willReturn($this->getOauthUserIdentifierFilterKeysMock());

        $factory = new OauthPermissionBusinessFactory();
        $factory->setConfig($configMock);

        //Act
        $userIdentifier = $this->getOauthPermissionFacade()->filterOauthUserIdentifier($userIdentifier);

        //Assert
        $this->assertSame($this->getOauthUserIdentifierFilterKeysMock(), $userIdentifier);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface
     */
    protected function createPermissionPluginMock(): MockObject
    {
        $mock = $this->getMockBuilder(PermissionPluginInterface::class)
            ->setMethods(['getKey'])
            ->getMock();
        $mock->method('getKey')
            ->willReturn(static::PERMISSION_PLUGIN_KEY);

        return $mock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface
     */
    protected function createPermissionStoragePluginStub(): PermissionStoragePluginInterface
    {
        $permissionStoragePluginStub = $this->getMockBuilder(PermissionStoragePluginInterface::class)
            ->setMethods(['getPermissionCollection'])
            ->getMock();
        $permissionStoragePluginStub->method('getPermissionCollection')
            ->willReturn($this->createPermissionCollectionTransfer());

        return $permissionStoragePluginStub;
    }

    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    protected function createPermissionCollectionTransfer(): PermissionCollectionTransfer
    {
        $permissionTransfer = (new PermissionTransfer())->setKey(static::PERMISSION_PLUGIN_KEY);

        return (new PermissionCollectionTransfer())->addPermission($permissionTransfer);
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\OauthPermission\Business\OauthPermissionFacadeInterface
     */
    protected function getOauthPermissionFacade(): OauthPermissionFacadeInterface
    {
        return $this->tester->getFacade();
    }

    /**
     * @return array
     */
    protected function getUserIdentifierArray(): array
    {
        return [
            'id_company_user' => '94d58692-c117-5466-8b9f-2ba32dd87c43',
            'customer_reference' => 'DE--1',
            'id_customer' => 1,
            'permissions' => [
                static::PERMISSION_PLUGIN_KEY,
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getUserIdentifierFilteredArray(): array
    {
        return [
            'id_company_user' => '94d58692-c117-5466-8b9f-2ba32dd87c43',
            'customer_reference' => 'DE--1',
            'id_customer' => 1,
        ];
    }

    /**
     * @return array
     */
    protected function getOauthUserIdentifierFilterKeysMock(): array
    {
        return [
            static::KEY_PERMISSIONS,
        ];
    }
}
