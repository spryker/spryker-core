<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\MerchantRelationship\Client;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Spryker\Client\MerchantRelationship\Dependency\Client\MerchantRelationshipToZedRequestClientInterface;
use Spryker\Client\MerchantRelationship\MerchantRelationshipDependencyProvider;
use SprykerTest\Client\MerchantRelationship\MerchantRelationshipClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group MerchantRelationship
 * @group Client
 * @group MerchantRelationshipClientTest
 * Add your own group annotations below this line
 */
class MerchantRelationshipClientTest extends Unit
{
    /**
     * @var \SprykerTest\Client\MerchantRelationship\MerchantRelationshipClientTester
     */
    protected MerchantRelationshipClientTester $tester;

    /**
     * @return void
     */
    public function testGetMerchantRelationshipCollectionMakesZedCall(): void
    {
        // Assert
        $merchantRelationshipToZedRequestClientMock = $this->getMerchantRelationshipToZedRequestClientMock();
        $merchantRelationshipToZedRequestClientMock->expects($this->once())
            ->method('call')
            ->willReturn(new MerchantRelationshipCollectionTransfer());

        // Arrange
        $this->tester->setDependency(
            MerchantRelationshipDependencyProvider::CLIENT_ZED_REQUEST,
            $merchantRelationshipToZedRequestClientMock,
        );

        $merchantRelationshipCriteriaTransfer = (new MerchantRelationshipCriteriaTransfer());

        // Act
        $this->tester->getClient()->getMerchantRelationshipCollection($merchantRelationshipCriteriaTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\MerchantRelationship\Dependency\Client\MerchantRelationshipToZedRequestClientInterface
     */
    protected function getMerchantRelationshipToZedRequestClientMock(): MerchantRelationshipToZedRequestClientInterface
    {
        return $this->getMockBuilder(MerchantRelationshipToZedRequestClientInterface::class)
            ->getMock();
    }
}
