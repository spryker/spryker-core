<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\OauthBackendApi\Plugin\GlueApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer;
use Spryker\Glue\Kernel\Backend\Container;
use Spryker\Glue\OauthBackendApi\Dependency\Facade\OauthBackendApiToOauthFacadeInterface;
use Spryker\Glue\OauthBackendApi\OauthBackendApiConfig;
use Spryker\Glue\OauthBackendApi\OauthBackendApiDependencyProvider;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group OauthBackendApi
 * @group Plugin
 * @group GlueApplication
 * @group BackendApiAccessTokenValidatorPluginTest
 * Add your own group annotations below this line
 */
class BackendApiAccessTokenValidatorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\OauthBackendApi\OauthBackendApiTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateReturnsTrueWithoutToken(): void
    {
        //Act
        $result = $this->tester->createBackendApiAccessTokenValidatorPlugin()->validate(new GlueRequestTransfer());

        //Assert
        $this->assertTrue($result->getIsValid());
    }

    /**
     * @return void
     */
    public function testValidateReturnsFalseWithInvalidToken(): void
    {
        //Arrange
        $this->tester->setDependency(OauthBackendApiDependencyProvider::FACADE_OAUTH, function (Container $container) {
            $oauthFacade = $this->createMock(OauthBackendApiToOauthFacadeInterface::class);
            $oauthFacade->method('validateAccessToken')
                ->willReturn((new OauthAccessTokenValidationResponseTransfer())->setIsValid(false));

            return $oauthFacade;
        });
        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setMeta([$this->tester::AUTHORIZATION => [0 => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9']]);

        //Act
        $result = $this->tester->createBackendApiAccessTokenValidatorPlugin()->validate($glueRequestTransfer);

        //Assert
        $this->assertFalse($result->getIsValid());

        $this->assertCount(1, $result->getErrors());
        $glueErrorTransfer = $result->getErrors()->getArrayCopy()[0];
        $this->assertSame(OauthBackendApiConfig::RESPONSE_CODE_ACCESS_CODE_INVALID, $glueErrorTransfer->getCode());
        $this->assertSame(Response::HTTP_UNAUTHORIZED, $glueErrorTransfer->getStatus());
        $this->assertSame(OauthBackendApiConfig::RESPONSE_DETAIL_INVALID_ACCESS_TOKEN, $glueErrorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testValidateReturnsTrueWithValidToken(): void
    {
        //Arrange
        $this->tester->setDependency(OauthBackendApiDependencyProvider::FACADE_OAUTH, function (Container $container) {
            $oauthFacade = $this->createMock(OauthBackendApiToOauthFacadeInterface::class);
            $oauthFacade->method('validateAccessToken')
                ->willReturn((new OauthAccessTokenValidationResponseTransfer())->setIsValid(true));

            return $oauthFacade;
        });
        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setMeta([$this->tester::AUTHORIZATION => [0 => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9']]);

        //Act
        $result = $this->tester->createBackendApiAccessTokenValidatorPlugin()->validate($glueRequestTransfer);

        //Assert
        $this->assertTrue($result->getIsValid());
    }
}
