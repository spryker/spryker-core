<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueHttp\Plugin;

use Codeception\Test\Unit;
use Spryker\Glue\GlueHttp\Plugin\GlueApplication\HttpCommunicationProtocolPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueHttp
 * @group Plugin
 * @group HttpCommunicationProtocolPluginTest
 * Add your own group annotations below this line
 */
class HttpCommunicationProtocolPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueHttp\GlueHttpTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const FIRST_FIELD_NAME = 'field1';

    /**
     * @var string
     */
    protected const FIRST_FIELD_VALUE = 'value1';

    /**
     * @return void
     */
    public function testHttpCommunicationProtocolPluginIsApplicable(): void
    {
        //Act
        $httpCommunicationProtocolPlugin = new HttpCommunicationProtocolPlugin();
        $result = $httpCommunicationProtocolPlugin->isApplicable();

        //Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testHttpCommunicationProtocolPluginExtractRequest(): void
    {
        //Arrange
        $_GET[static::FIRST_FIELD_NAME] = static::FIRST_FIELD_VALUE;
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();

        //Act
        $httpCommunicationProtocolPlugin = new HttpCommunicationProtocolPlugin();
        $glueRequestTransfer = $httpCommunicationProtocolPlugin->extractRequest($glueRequestTransfer);

        // Assert
        $queryFields = $glueRequestTransfer->getQueryFields();
        $this->assertCount(1, $queryFields);
        $this->assertArrayHasKey(static::FIRST_FIELD_NAME, $queryFields);
        $this->assertSame(static::FIRST_FIELD_VALUE, $queryFields[static::FIRST_FIELD_NAME]);
    }
}
