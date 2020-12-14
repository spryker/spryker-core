<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityOauthUser\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ResourceOwnerRequestBuilder;
use Generated\Shared\Transfer\ResourceOwnerRequestTransfer;
use Generated\Shared\Transfer\ResourceOwnerResponseTransfer;
use Generated\Shared\Transfer\ResourceOwnerTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin\OauthUserClientStrategyPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityOauthUser
 * @group Business
 * @group Facade
 * @group GetResourceOwnerTest
 * Add your own group annotations below this line
 */
class GetResourceOwnerTest extends Unit
{
    protected const RESOURCE_OWNER_WRONG_REQUEST_CODE = 'test';
    protected const SOME_CODE = 'SOME_CODE';
    protected const SOME_STATE = 'SOME_STATE';

    /**
     * @var \SprykerTest\Zed\SecurityOauthUser\SecurityOauthUserBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetResourceOwnerShouldGetResourceOwner(): void
    {
        // Arrange
        $this->tester->setOauthUserClientStrategyPlugin($this->createOauthUserClientStrategyPluginMock(true));

        $resourceOwnerRequestTransfer = (new ResourceOwnerRequestBuilder())->build();

        // Act
        $resourceOwnerResponseTransfer = $this->tester
            ->getSecurityOauthUserFacade()
            ->getResourceOwner($resourceOwnerRequestTransfer);

        //Assert
        $this->assertTrue(
            $resourceOwnerResponseTransfer->getIsSuccessful(),
            'Expected that `IsSuccessful` flag equals to true.'
        );
        $this->assertNotNull(
            $resourceOwnerResponseTransfer->getResourceOwner(),
            'Expected that resource owner must be provided.'
        );
    }

    /**
     * @return void
     */
    public function testGetResourceOwnerWithWrongRequestShouldNotGetResourceOwner(): void
    {
        // Arrange
        $this->tester->setOauthUserClientStrategyPlugin($this->createOauthUserClientStrategyPluginMock(false));
        $resourceOwnerRequestTransfer = (new ResourceOwnerRequestBuilder())->build();

        // Act
        $resourceOwnerResponseTransfer = $this->tester
            ->getSecurityOauthUserFacade()
            ->getResourceOwner($resourceOwnerRequestTransfer);

        // Assert
        $this->assertFalse(
            $resourceOwnerResponseTransfer->getIsSuccessful(),
            'Expected that `IsSuccessful` flag equals to false.'
        );
        $this->assertNull(
            $resourceOwnerResponseTransfer->getResourceOwner(),
            'Expected that resource owner must not be provided.'
        );
    }

    /**
     * @dataProvider getResourceOwnerThrowExceptionWhenRequiredDataIsNotProvidedDataProvider
     *
     * @param \Generated\Shared\Transfer\ResourceOwnerRequestTransfer $resourceOwnerRequestTransfer
     *
     * @return void
     */
    public function testGetResourceOwnerThrowExceptionWhenRequiredDataIsNotProvided(
        ResourceOwnerRequestTransfer $resourceOwnerRequestTransfer
    ): void {
        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getSecurityOauthUserFacade()->getResourceOwner($resourceOwnerRequestTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\ResourceOwnerRequestTransfer[][]
     */
    public function getResourceOwnerThrowExceptionWhenRequiredDataIsNotProvidedDataProvider(): array
    {
        return [
            [(new ResourceOwnerRequestTransfer())->setCode(static::SOME_CODE)],
            [(new ResourceOwnerRequestTransfer())->setState(static::SOME_STATE)],
        ];
    }

    /**
     * @param bool $successFlow
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin\OauthUserClientStrategyPluginInterface
     */
    protected function createOauthUserClientStrategyPluginMock(
        bool $successFlow
    ): OauthUserClientStrategyPluginInterface {
        $oauthUserClientStrategyPluginMock = $this->getMockBuilder(OauthUserClientStrategyPluginInterface::class)
            ->getMock();

        $oauthUserClientStrategyPluginMock
            ->method('isApplicable')
            ->willReturn($successFlow);

        $resourceOwnerResponseTransfer = (new ResourceOwnerResponseTransfer())
            ->setIsSuccessful($successFlow);

        if ($successFlow) {
            $resourceOwnerResponseTransfer->setResourceOwner(new ResourceOwnerTransfer());
        }

        $oauthUserClientStrategyPluginMock
            ->method('getResourceOwner')
            ->willReturn($resourceOwnerResponseTransfer);

        return $oauthUserClientStrategyPluginMock;
    }
}
