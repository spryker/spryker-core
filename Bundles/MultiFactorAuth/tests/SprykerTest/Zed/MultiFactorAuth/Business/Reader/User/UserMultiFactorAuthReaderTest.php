<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\MultiFactorAuth\Business\Reader\User;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface;
use Spryker\Zed\MultiFactorAuth\Business\Reader\User\UserMultiFactorAuthReader;
use Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MultiFactorAuth
 * @group Business
 * @group Reader
 * @group User
 * @group UserMultiFactorAuthReaderTest
 * Add your own group annotations below this line
 */
class UserMultiFactorAuthReaderTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_TYPE = 'test-type';

    /**
     * @var int
     */
    protected const TEST_USER_ID = 1;

    /**
     * @var \Generated\Shared\Transfer\UserTransfer
     */
    protected UserTransfer $userTransfer;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface
     */
    protected $multiFactorAuthPluginMock;

    /**
     * @var array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface>
     */
    protected array $userMultiFactorAuthPlugins;

    /**
     * @var \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    protected MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollection;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface
     */
    protected $multiFactorAuthRepositoryMock;

    /**
     * @var \Spryker\Zed\MultiFactorAuth\Business\Reader\User\UserMultiFactorAuthReader
     */
    protected UserMultiFactorAuthReader $userMultiFactorAuthReader;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userTransfer = (new UserTransfer())->setIdUser(static::TEST_USER_ID);

        $this->multiFactorAuthPluginMock = $this->createMock(MultiFactorAuthPluginInterface::class);
        $this->multiFactorAuthPluginMock
            ->method('getName')
            ->willReturn(static::TEST_TYPE);

        $this->userMultiFactorAuthPlugins = [$this->multiFactorAuthPluginMock];

        $multiFactorAuthTransfer = (new MultiFactorAuthTransfer())
            ->setType(static::TEST_TYPE)
            ->setStatus(MultiFactorAuthConstants::STATUS_ACTIVE)
            ->setUser($this->userTransfer);

        $this->multiFactorAuthTypesCollection = new MultiFactorAuthTypesCollectionTransfer();
        $this->multiFactorAuthTypesCollection->addMultiFactorAuth($multiFactorAuthTransfer);

        $this->multiFactorAuthRepositoryMock = $this->getMockBuilder(MultiFactorAuthRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->userMultiFactorAuthReader = new UserMultiFactorAuthReader($this->multiFactorAuthRepositoryMock, $this->userMultiFactorAuthPlugins);
    }

    /**
     * @return void
     */
    public function testGetAvailableUserMultiFactorAuthTypesReturnsCollectionWithProperData(): void
    {
        // Arrange
        $multiFactorAuthCriteriaTransfer = (new MultiFactorAuthCriteriaTransfer())
            ->setUser($this->userTransfer);
        $this->multiFactorAuthRepositoryMock
            ->expects($this->once())
            ->method('getUserMultiFactorAuthTypes')
            ->with($multiFactorAuthCriteriaTransfer)
            ->willReturn($this->multiFactorAuthTypesCollection);

        // Act
        $multiFactorAuthTypesCollectionTransfer = $this->userMultiFactorAuthReader->getAvailableUserMultiFactorAuthTypes($multiFactorAuthCriteriaTransfer);

        // Assert
        $this->assertInstanceOf(MultiFactorAuthTypesCollectionTransfer::class, $multiFactorAuthTypesCollectionTransfer);
        $this->assertCount(1, $multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes());

        $multiFactorAuthTransfer = $multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes()->getIterator()->current();
        $this->assertSame(static::TEST_TYPE, $multiFactorAuthTransfer->getType());
        $this->assertSame(MultiFactorAuthConstants::STATUS_ACTIVE, $multiFactorAuthTransfer->getStatus());
    }
}
