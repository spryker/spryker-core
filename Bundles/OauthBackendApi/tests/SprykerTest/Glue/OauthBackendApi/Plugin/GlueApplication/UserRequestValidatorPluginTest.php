<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\OauthBackendApi\Plugin\GlueApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestUserTransfer;
use Spryker\Glue\OauthBackendApi\OauthBackendApiConfig;
use Spryker\Glue\OauthBackendApi\Plugin\GlueApplication\UserRequestValidatorPlugin;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group OauthBackendApi
 * @group Plugin
 * @group GlueApplication
 * @group UserRequestValidatorPluginTest
 * Add your own group annotations below this line
 */
class UserRequestValidatorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\OauthBackendApi\OauthBackendApiTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateReturnsTrueWithMissingMeta(): void
    {
        //Act
        $result = (new UserRequestValidatorPlugin())->validate(new GlueRequestTransfer());

        //Assert
        $this->assertTrue($result->getIsValid());
    }

    /**
     * @return void
     */
    public function testValidateReturnsTrueWithMeta(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())->setMeta(['user-agent' => ['SprykerBrowser1.0']]);

        //Act
        $result = (new UserRequestValidatorPlugin())->validate($glueRequestTransfer);

        //Assert
        $this->assertTrue($result->getIsValid());
    }

    /**
     * @return void
     */
    public function testValidateReturnsTrueWithMetaAndRequestUserTransfer(): void
    {
        //Arrange
        $glueRequestUserTransfer = (new GlueRequestUserTransfer())->setNaturalIdentifier('DE--999')->setSurrogateIdentifier(999);
        $glueRequestTransfer = (new GlueRequestTransfer())->setMeta(['authorization' => [0 => 'xxxx']])->setRequestUser($glueRequestUserTransfer);

        //Act
        $result = (new UserRequestValidatorPlugin())->validate($glueRequestTransfer);

        //Assert
        $this->assertTrue($result->getIsValid());
    }

    /**
     * @return void
     */
    public function testValidateReturnsFalseWithMetaAndWithoutRequestUserTransfer(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())->setMeta(['authorization' => [0 => 'xxxx']])->setRequestUser(null);

        //Act
        $result = (new UserRequestValidatorPlugin())->validate($glueRequestTransfer);

        //Assert
        $this->assertFalse($result->getIsValid());
        $glueErrorTransfer = $result->getErrors()->getArrayCopy()[0];
        $this->assertSame(OauthBackendApiConfig::RESPONSE_CODE_ACCESS_CODE_INVALID, $glueErrorTransfer->getCode());
        $this->assertSame(Response::HTTP_BAD_REQUEST, $glueErrorTransfer->getStatus());
        $this->assertSame(OauthBackendApiConfig::RESPONSE_DETAIL_INVALID_ACCESS_TOKEN, $glueErrorTransfer->getMessage());
    }
}
