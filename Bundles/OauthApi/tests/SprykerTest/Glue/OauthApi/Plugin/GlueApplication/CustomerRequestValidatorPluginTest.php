<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\OauthApi\Plugin\GlueApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestCustomerTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\OauthApi\OauthApiConfig;
use Spryker\Glue\OauthApi\Plugin\GlueApplication\CustomerRequestValidatorPlugin;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group OauthApi
 * @group Plugin
 * @group GlueApplication
 * @group CustomerRequestValidatorPluginTest
 * Add your own group annotations below this line
 */
class CustomerRequestValidatorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\OauthApi\OauthApiTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateReturnsTrueWithMissingMeta(): void
    {
        //Act
        $result = (new CustomerRequestValidatorPlugin())->validate(new GlueRequestTransfer());

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
        $result = (new CustomerRequestValidatorPlugin())->validate($glueRequestTransfer);

        //Assert
        $this->assertTrue($result->getIsValid());
    }

    /**
     * @return void
     */
    public function testValidateReturnsTrueWithMetaAndRequestCustomerTransfer(): void
    {
        //Arrange
        $glueRequestCustomerTransfer = (new GlueRequestCustomerTransfer())->setNaturalIdentifier('DE--999')->setSurrogateIdentifier(999);
        $glueRequestTransfer = (new GlueRequestTransfer())->setMeta(['authorization' => [0 => 'xxxx']])->setRequestCustomer($glueRequestCustomerTransfer);

        //Act
        $result = (new CustomerRequestValidatorPlugin())->validate($glueRequestTransfer);

        //Assert
        $this->assertTrue($result->getIsValid());
    }

    /**
     * @return void
     */
    public function testValidateReturnsFalseWithMetaAndWithoutRequestCustomerTransfer(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())->setMeta(['authorization' => [0 => 'xxxx']])->setRequestCustomer(null);

        //Act
        $result = (new CustomerRequestValidatorPlugin())->validate($glueRequestTransfer);

        //Assert
        $this->assertFalse($result->getIsValid());
        $glueErrorTransfer = $result->getErrors()->getArrayCopy()[0];
        $this->assertSame(OauthApiConfig::RESPONSE_CODE_ACCESS_CODE_INVALID, $glueErrorTransfer->getCode());
        $this->assertSame(Response::HTTP_BAD_REQUEST, $glueErrorTransfer->getStatus());
        $this->assertSame(OauthApiConfig::RESPONSE_DETAIL_INVALID_ACCESS_TOKEN, $glueErrorTransfer->getMessage());
    }
}
