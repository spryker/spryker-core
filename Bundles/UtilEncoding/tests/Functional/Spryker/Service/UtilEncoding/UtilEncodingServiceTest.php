<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Service\UtilEncoding\Business;

use Codeception\TestCase\Test;
use Spryker\Service\UtilEncoding\UtilEncodingService;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group UtilEncoding
 * @group Business
 * @group UtilEncodingFacadeTest
 */
class UtilEncodingServiceTest extends Test
{

    const JSON_ENCODED_VALUE = '{"1":"one","2":"two"}';

    /**
     * @var array
     */
    protected $jsonData = [1 => 'one', 2 => 'two'];

    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingService
     */
    protected $utilEncodingService;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->utilEncodingService = new UtilEncodingService();
    }

    /**
     * @return void
     */
    public function testEncodeJsonShouldReturnJsonEncodedValue()
    {
        $jsonEncodeValue = $this->utilEncodingService->encodeJson($this->jsonData);

        $this->assertEquals(self::JSON_ENCODED_VALUE, $jsonEncodeValue);
    }

    /**
     * @return void
     */
    public function testDecodeJsonShouldReturnAssocArray()
    {
        $jsonDecodeValue = $this->utilEncodingService->decodeJson(self::JSON_ENCODED_VALUE, true);

        $this->assertEquals($this->jsonData, $jsonDecodeValue);
    }

    /**
     * @return void
     */
    public function testDecodeJsonWhenAssocFlagIsOffShouldReturnStdObject()
    {
        $jsonDecodeValue = $this->utilEncodingService->decodeJson(self::JSON_ENCODED_VALUE);

        $this->assertEquals((object)$this->jsonData, $jsonDecodeValue);
    }

}
