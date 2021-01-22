<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthRevoke\Business;

use ArrayObject;
use Codeception\Test\Unit;
use DateTime;
use DateTimeImmutable;
use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer;
use Orm\Zed\Oauth\Persistence\SpyOauthClient;
use Orm\Zed\OauthRevoke\Persistence\SpyOauthRefreshToken;
use Orm\Zed\OauthRevoke\Persistence\SpyOauthRefreshTokenQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Oauth\Business\Model\League\Entities\AccessTokenEntity;
use Spryker\Zed\Oauth\Business\Model\League\Entities\ClientEntity;
use Spryker\Zed\Oauth\Business\Model\League\Entities\RefreshTokenEntity;
use Spryker\Zed\OauthRevoke\Business\OauthRevokeFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OauthRevoke
 * @group Business
 * @group Facade
 * @group OauthRevokeFacadeTest
 * Add your own group annotations below this line
 */
class OauthRevokeFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\OauthRevoke\OauthRevokeBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\OauthRevoke\Business\OauthRevokeFacade
     */
    protected $oauthRevokeFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->oauthRevokeFacade = new OauthRevokeFacade();
    }

    /**
     * @return void
     */
    public function testDeleteExpiredRefreshTokens(): void
    {
        // Arrange
        $this->tester->deleteAllOauthRefreshTokens();
        $this->tester->persistOauthRefreshToken('identifier');
        $criteriaTransfer = new OauthTokenCriteriaFilterTransfer();
        $criteriaTransfer->setExpiresAt((new DateTimeImmutable())->format('Y-m-d'));

        // Act
        $count = $this->oauthRevokeFacade->deleteExpiredRefreshTokens($criteriaTransfer);

        // Assert
        $this->assertSame(1, $count);
    }

    /**
     * @return void
     */
    public function testFindRefreshToken(): void
    {
        // Arrange
        $this->tester->deleteAllOauthRefreshTokens();

        $expectedOauthRefreshToken = $this->tester->persistOauthRefreshToken('identifier');

        $criteriaTransfer = new OauthTokenCriteriaFilterTransfer();
        $criteriaTransfer->setIdentifier($expectedOauthRefreshToken->getIdentifier());

        // Act
        $refreshToken = $this->oauthRevokeFacade->findRefreshToken($criteriaTransfer);

        // Assert
        $this->assertSame($expectedOauthRefreshToken->getIdentifier(), $refreshToken->getIdentifier());
    }

    /**
     * @return void
     */
    public function testGetRefreshTokens(): void
    {
        // Arrange
        $this->tester->deleteAllOauthRefreshTokens();

        $this->tester->persistOauthRefreshToken('identifier1');
        $expectedOauthRefreshToken = $this->tester->persistOauthRefreshToken('identifier2');

        $criteriaTransfer = new OauthTokenCriteriaFilterTransfer();
        $criteriaTransfer->setCustomerReference($expectedOauthRefreshToken->getCustomerReference());

        // Act
        $refreshTokens = $this->oauthRevokeFacade->getRefreshTokens($criteriaTransfer);

        // Assert
        $this->assertSame(2, $refreshTokens->getOauthRefreshTokens()->count());
    }

    /**
     * @return void
     */
    public function testRevokeRefreshToken(): void
    {
        // Arrange
        $this->tester->deleteAllOauthRefreshTokens();

        $expectedOauthRefreshToken = $this->tester->persistOauthRefreshToken('identifier');

        $oauthRefreshTokenTransfer = (new OauthRefreshTokenTransfer())
            ->setIdentifier($expectedOauthRefreshToken->getIdentifier());

        // Act
        $this->oauthRevokeFacade->revokeRefreshToken($oauthRefreshTokenTransfer);

        // Assert
        $oauthRefreshTokenEntity = SpyOauthRefreshTokenQuery::create()
            ->filterByRevokedAt(null, Criteria::ISNOTNULL)
            ->filterByIdentifier($oauthRefreshTokenTransfer->getIdentifier())
            ->findOne();

        $this->assertSame($expectedOauthRefreshToken->getIdentifier(), $oauthRefreshTokenEntity->getIdentifier());
        $this->assertNotNull($oauthRefreshTokenEntity->getRevokedAt());
    }

    /**
     * @return void
     */
    public function testRevokeAllRefreshTokens(): void
    {
        // Arrange
        $this->tester->deleteAllOauthRefreshTokens();

        $expectedOauthRefreshToken1 = $this->tester->persistOauthRefreshToken('identifier1');
        $expectedOauthRefreshToken2 = $this->tester->persistOauthRefreshToken('identifier2');

        $oauthRefreshTokenTransfer = (new OauthRefreshTokenTransfer())
            ->setIdentifier($expectedOauthRefreshToken1->getIdentifier());

        $oauthRefreshTokenTransfer2 = (new OauthRefreshTokenTransfer())
            ->setIdentifier($expectedOauthRefreshToken2->getIdentifier());

        $collection = new ArrayObject([$oauthRefreshTokenTransfer, $oauthRefreshTokenTransfer2]);

        // Act
        $this->oauthRevokeFacade->revokeAllRefreshTokens($collection);

        // Assert
        $oauthRefreshTokenEntity = SpyOauthRefreshTokenQuery::create()
            ->filterByRevokedAt(null, Criteria::ISNOTNULL)
            ->find();

        $this->assertSame(2, $oauthRefreshTokenEntity->count());
    }

    /**
     * @return void
     */
    public function testPositiveIsRefreshTokenRevoked(): void
    {
        // Arrange
        $this->tester->deleteAllOauthRefreshTokens();

        $expectedOauthRefreshToken = $this->tester->persistOauthRefreshToken('identifier');

        $oauthRefreshTokenTransfer = (new OauthRefreshTokenTransfer())
            ->setIdentifier($expectedOauthRefreshToken->getIdentifier());

        // Act
        $isRevoked = $this->oauthRevokeFacade->isRefreshTokenRevoked($oauthRefreshTokenTransfer);

        // Assert
        $this->assertFalse($isRevoked);
    }

    /**
     * @return void
     */
    public function testNegativeIsRefreshTokenRevoked(): void
    {
        // Arrange
        $this->tester->deleteAllOauthRefreshTokens();

        $oauthClient = new SpyOauthClient();
        $oauthClient
            ->setName('clientName')
            ->setIdentifier('identifier')
            ->save();

        $expectedOauthRefreshToken = new SpyOauthRefreshToken();
        $expectedOauthRefreshToken
            ->setIdentifier('identifier')
            ->setUserIdentifier('user identifier')
            ->setFkOauthClient($oauthClient->getIdentifier())
            ->setCustomerReference('customer reference')
            ->setExpiresAt((new DateTimeImmutable())->format('Y-m-d'))
            ->setRevokedAt((new DateTimeImmutable())->format('Y-m-d'))
            ->save();

        $oauthRefreshTokenTransfer = (new OauthRefreshTokenTransfer())
            ->setIdentifier($expectedOauthRefreshToken->getIdentifier());

        // Act
        $isRevoked = $this->oauthRevokeFacade->isRefreshTokenRevoked($oauthRefreshTokenTransfer);

        // Assert
        $this->assertTrue($isRevoked);
    }

    /**
     * @return void
     */
    public function testSaveRefreshToken(): void
    {
        // Arrange
        $this->tester->deleteAllOauthRefreshTokens();

        $clientEntity = new ClientEntity();
        $clientEntity->setIdentifier('frontend');

        $userIdentifier = json_encode([
            'customer_reference' => 'DE--test',
        ]);

        $accessToken = new AccessTokenEntity();
        $accessToken->setClient($clientEntity);
        $accessToken->setUserIdentifier($userIdentifier);

        $refreshToken = new RefreshTokenEntity();
        $refreshToken->setExpiryDateTime(new DateTimeImmutable());
        $refreshToken->setAccessToken($accessToken);
        $refreshToken->setIdentifier('identifier');

        // Act
        $this->oauthRevokeFacade->saveRefreshToken($refreshToken);

        // Assert
        $oauthRefreshTokenEntity = SpyOauthRefreshTokenQuery::create()
            ->findOne();

        $this->assertNotEmpty($oauthRefreshTokenEntity);
        $this->assertSame($refreshToken->getIdentifier(), $oauthRefreshTokenEntity->getIdentifier());
    }

    /**
     * @return void
     */
    public function testSaveRefreshTokenFromTransfer(): void
    {
        // Arrange
        $this->tester->deleteAllOauthRefreshTokens();
        $userIdentifier = json_encode([
            'customer_reference' => 'DE--test',
        ]);
        $oauthRefreshTokenTransfer = (new OauthRefreshTokenTransfer())
            ->setIdentifier('identifier1')
            ->setCustomerReference('DE--test')
            ->setUserIdentifier(json_encode($userIdentifier))
            ->setExpiresAt((new DateTime())->format('Y-m-d H:i:s'))
            ->setIdOauthClient('frontend');
        // Act
        $this->oauthRevokeFacade->saveRefreshTokenFromTransfer($oauthRefreshTokenTransfer);

        // Assert
        $oauthRefreshTokenEntity = SpyOauthRefreshTokenQuery::create()
            ->findOne();

        $this->assertNotEmpty($oauthRefreshTokenEntity);
        $this->assertSame('identifier1', $oauthRefreshTokenEntity->getIdentifier());
    }
}
