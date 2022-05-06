<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\OauthApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer;
use Spryker\Glue\OauthApi\Dependency\Client\OauthApiToOauthClientInterface;
use Spryker\Glue\OauthApi\OauthApiConfig;
use Spryker\Glue\OauthApi\OauthApiDependencyProvider;
use Spryker\Glue\OauthApi\Plugin\AccessTokenValidatorPlugin;
use Spryker\Service\Container\Container;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group OauthApi
 * @group Plugin
 * @group AccessTokenValidatorPluginTest
 * Add your own group annotations below this line
 */
class AccessTokenValidatorPluginTest extends Unit
{
    /**
     * @var string
     */
    public const HTTP_AUTHORIZATION = 'HTTP_AUTHORIZATION';

    /**
     * @var \SprykerTest\Glue\OauthApi\OauthApiTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidatorIsNotProtected(): void
    {
        //Act
        $result = (new AccessTokenValidatorPlugin())->validate(new GlueRequestTransfer());

        //Assert
        $this->assertTrue($result->getIsValid());
    }

    /**
     * @return void
     */
    public function testValidatorEmptyAuth(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())->setHttpRequestAttributes(['is-protected' => true]);

        //Act
        $result = (new AccessTokenValidatorPlugin())->validate($glueRequestTransfer);

        //Assert
        $this->assertInstanceOf(GlueRequestValidationTransfer::class, $result);

        $this->assertFalse($result->getIsValid());

        $glueErrorTransfer = $result->getErrors()->getArrayCopy()[0];
        $this->assertSame(OauthApiConfig::RESPONSE_CODE_FORBIDDEN, $glueErrorTransfer->getCode());
        $this->assertSame(Response::HTTP_FORBIDDEN, $glueErrorTransfer->getStatus());
        $this->assertSame(OauthApiConfig::RESPONSE_DETAIL_MISSING_ACCESS_TOKEN, $glueErrorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testValidatorTokenNotValid(): void
    {
        //Arrange
        $this->tester->setDependency(OauthApiDependencyProvider::CLIENT_OAUTH, function (Container $container) {
            $oauthFacade = $this->createMock(OauthApiToOauthClientInterface::class);
            $oauthFacade->method('validateOauthAccessToken')
                ->willReturn((new OauthAccessTokenValidationResponseTransfer())->setIsValid(false));

            return $oauthFacade;
        });
        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setHttpRequestAttributes(['is-protected' => true])
            ->setMeta(['authorization' => [0 => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9']]);

        //Act
        $result = (new AccessTokenValidatorPlugin())->validate($glueRequestTransfer);

        //Assert
        $this->assertFalse($result->getIsValid());

        $glueErrorTransfer = $result->getErrors()->getArrayCopy()[0];
        $this->assertSame(OauthApiConfig::RESPONSE_CODE_ACCESS_CODE_INVALID, $glueErrorTransfer->getCode());
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $glueErrorTransfer->getStatus());
        $this->assertSame(OauthApiConfig::RESPONSE_DETAIL_INVALID_ACCESS_TOKEN, $glueErrorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testValidatorTokenValid(): void
    {
        //Arrange
        $this->tester->setDependency(OauthApiDependencyProvider::CLIENT_OAUTH, function (Container $container) {
            $oauthFacade = $this->createMock(OauthApiToOauthClientInterface::class);
            $oauthFacade->method('validateOauthAccessToken')
                ->willReturn((new OauthAccessTokenValidationResponseTransfer())->setIsValid(true));

            return $oauthFacade;
        });
        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setHttpRequestAttributes(['is-protected' => true])
            ->setMeta(['authorization' => [0 => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9']]);

        //Act
        $result = (new AccessTokenValidatorPlugin())->validate($glueRequestTransfer);

        //Assert
        $this->assertTrue($result->getIsValid());
    }
}
