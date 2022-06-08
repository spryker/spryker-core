<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\OauthApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\OauthApi\Plugin\CustomerRequestBuilderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group OauthApi
 * @group Plugin
 * @group CustomerRequestBuilderPluginTest
 * Add your own group annotations below this line
 */
class CustomerRequestBuilderPluginTest extends Unit
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
    public function testUserFinderWhenAuthorizationHeaderNotExist(): void
    {
        //Act
        $glueRequestTransfer = $this->findCustomer(new GlueRequestTransfer());

        //Assert
        $this->assertEmpty($glueRequestTransfer->getRequestCustomer());
    }

    /**
     * @return void
     */
    public function testUserFinderThrowExceptionWhenAccessTokenInvalid(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())->setMeta(['authorization' => [0 => '']]);

        //Act
        $glueRequestTransfer = $this->findCustomer($glueRequestTransfer);

        //Assert
        $this->assertIsObject($glueRequestTransfer);
        $this->assertEmpty($glueRequestTransfer->getRequestCustomer());
    }

    /**
     * @return void
     */
    public function testUserFinderWithWrongAccessToken(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())->setMeta(['authorization' => [0 => 'Bearer eyJ0eXAiOiJKV1QiLC']]);

        //Act
        $glueRequestTransfer = $this->findCustomer($glueRequestTransfer);

        //Assert
        $this->assertIsObject($glueRequestTransfer);
        $this->assertEmpty($glueRequestTransfer->getRequestCustomer());
    }

    /**
     * @return void
     */
    public function testUserFinderWhenAccessTokenIsValid(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())->setMeta(['authorization' => [0 => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJmcm9udGVuZCIsImp0aSI6IjgyZTViYTc3ZGI3NTc0ZmQ0NTUzYTBhZDNjM2JlNzg5OTkzZjU1MjA3ZDQ0ZWFiYmI0YWNjYmE4OTYwODQzYzE2OGQwY2YzY2FhZDc2NTM0IiwiaWF0IjoxNjQ2MjM4ODg5LjQ0NTExODksIm5iZiI6MTY0NjIzODg4OS40NDUxMzM5LCJleHAiOjE2NDYyNjc2ODkuMzY1NjM5OSwic3ViIjoie1wiaWRfY29tcGFueV91c2VyXCI6XCI2NWI1OTFmNy1iN2Y1LTUwYjktOTMxZS03NDBmNjg2NDVjMjFcIixcImlkX2FnZW50XCI6bnVsbCxcImN1c3RvbWVyX3JlZmVyZW5jZVwiOlwiREUtLTMzXCIsXCJpZF9jdXN0b21lclwiOjI1LFwicGVybWlzc2lvbnNcIjp7XCJwZXJtaXNzaW9uc1wiOlt7XCJpZF9wZXJtaXNzaW9uXCI6MSxcImtleVwiOlwiUmVhZFNoYXJlZENhcnRQZXJtaXNzaW9uUGx1Z2luXCIsXCJjb25maWd1cmF0aW9uXCI6e1wiaWRfcXVvdGVfY29sbGVjdGlvblwiOltcIjI2XCJdfSxcImNvbmZpZ3VyYXRpb25fc2lnbmF0dXJlXCI6XCJbXVwiLFwiaWRfY29tcGFueV9yb2xlXCI6bnVsbCxcImlzX2luZnJhc3RydWN0dXJhbFwiOm51bGx9LHtcImlkX3Blcm1pc3Npb25cIjoyLFwia2V5XCI6XCJXcml0ZVNoYXJlZENhcnRQZXJtaXNzaW9uUGx1Z2luXCIsXCJjb25maWd1cmF0aW9uXCI6e1wiaWRfcXVvdGVfY29sbGVjdGlvblwiOltcIjI2XCJdfSxcImNvbmZpZ3VyYXRpb25fc2lnbmF0dXJlXCI6XCJbXVwiLFwiaWRfY29tcGFueV9yb2xlXCI6bnVsbCxcImlzX2luZnJhc3RydWN0dXJhbFwiOm51bGx9LHtcImlkX3Blcm1pc3Npb25cIjpudWxsLFwia2V5XCI6XCJSZWFkU2hvcHBpbmdMaXN0UGVybWlzc2lvblBsdWdpblwiLFwiY29uZmlndXJhdGlvblwiOntcImlkX3Nob3BwaW5nX2xpc3RfY29sbGVjdGlvblwiOltdfSxcImNvbmZpZ3VyYXRpb25fc2lnbmF0dXJlXCI6W10sXCJpZF9jb21wYW55X3JvbGVcIjpudWxsLFwiaXNfaW5mcmFzdHJ1Y3R1cmFsXCI6bnVsbH0se1wiaWRfcGVybWlzc2lvblwiOm51bGwsXCJrZXlcIjpcIldyaXRlU2hvcHBpbmdMaXN0UGVybWlzc2lvblBsdWdpblwiLFwiY29uZmlndXJhdGlvblwiOntcImlkX3Nob3BwaW5nX2xpc3RfY29sbGVjdGlvblwiOltdfSxcImNvbmZpZ3VyYXRpb25fc2lnbmF0dXJlXCI6W10sXCJpZF9jb21wYW55X3JvbGVcIjpudWxsLFwiaXNfaW5mcmFzdHJ1Y3R1cmFsXCI6bnVsbH1dfX0iLCJzY29wZXMiOlsiY3VzdG9tZXIiXX0.HqXr-6yL4xWaVsIeL5xsvRJ8j6YP6O3zBWIYGAKfd6GPsNajRFKH8b4hidoUXE5lxGHcnoSupRjdabMaRUq6EqgoEDZM4jFJApXAcM4I2TFvu_uRSJMRdfw2YhPdK6yaPnp4xHXO9wS-C4Si8xVovFLNZZGzaVDso1RCy8iWwFXvGTITxb-cOlXZr6Lry6lwF0YRTSHypN0EtXMBt9mllpExSObQ6AclEJ6HjF_CiAPkBNQvYQy4Gd62ODwhXRUBWSr2-pbu-wtLB1zG5snCvNpXDROYdjHFSTn8MvQ-Jnlzf6IrsjtBxRTiZzC-jaDaQGMzriT82vqVQ6VBfsqrhQ']]);

        //Act
        $glueRequestTransfer = $this->findCustomer($glueRequestTransfer);

        //Assert
        $this->assertIsObject($glueRequestTransfer);
        $this->assertNotEmpty($glueRequestTransfer->getRequestCustomer());
        $this->assertSame('DE--33', $glueRequestTransfer->getRequestCustomer()->getNaturalIdentifier());
        $this->assertSame(25, $glueRequestTransfer->getRequestCustomer()->getSurrogateIdentifier());
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function findCustomer(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        $accessTokenCustomerFinderPlugin = new CustomerRequestBuilderPlugin();

        return $accessTokenCustomerFinderPlugin->build($glueRequestTransfer);
    }
}
