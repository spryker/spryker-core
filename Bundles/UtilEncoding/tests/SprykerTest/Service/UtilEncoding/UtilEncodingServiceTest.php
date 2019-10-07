<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilEncoding;

use Codeception\Test\Unit;
use Spryker\Service\UtilEncoding\Model\Json;
use Spryker\Service\UtilEncoding\UtilEncodingService;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group UtilEncoding
 * @group UtilEncodingServiceTest
 * Add your own group annotations below this line
 */
class UtilEncodingServiceTest extends Unit
{
    public const JSON_ENCODED_VALUE = '{"1":"one","2":"two"}';

    public const JSON_ENCODED_VALUE_PRETTY_PRINT = <<<JSON
{
    "1": "one",
    "2": "two"
}
JSON;

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
    public function testEncodeJsonWithDefaultOptions()
    {
        $jsonEncodeValue = $this->utilEncodingService->encodeJson($this->jsonData);

        $this->assertEquals(self::JSON_ENCODED_VALUE, $jsonEncodeValue);
    }

    /**
     * @return void
     */
    public function testEncodeString()
    {
        $jsonEncodeValue = $this->utilEncodingService->encodeJson('A string!');

        $this->assertEquals('"A string!"', $jsonEncodeValue);
    }

    /**
     * @return void
     */
    public function testEncodeBooleanAndNull()
    {
        $jsonEncodeValue = $this->utilEncodingService->encodeJson(true);

        $this->assertEquals('true', $jsonEncodeValue);

        $jsonEncodeValue = $this->utilEncodingService->encodeJson(false);

        $this->assertEquals('false', $jsonEncodeValue);

        $jsonEncodeValue = $this->utilEncodingService->encodeJson(null);

        $this->assertEquals('null', $jsonEncodeValue);
    }

    /**
     * @return void
     */
    public function testEncodeInvalid()
    {
        $jsonEncodeValue = $this->utilEncodingService->encodeJson(['x' => ['y' => 'z']], JSON_NUMERIC_CHECK, 1);

        $this->assertNull($jsonEncodeValue);
    }

    /**
     * @return void
     */
    public function testEncodeJsonWithPrettyPrintIncluded()
    {
        $jsonEncodeValue = $this->utilEncodingService->encodeJson($this->jsonData, Json::DEFAULT_OPTIONS | JSON_PRETTY_PRINT);

        $this->assertEquals(self::JSON_ENCODED_VALUE_PRETTY_PRINT, $jsonEncodeValue);
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

    /**
     * @return void
     */
    public function testDecodeString()
    {
        $jsonEncodeValue = $this->utilEncodingService->decodeJson('"A string!"');

        $this->assertEquals('A string!', $jsonEncodeValue);
    }

    /**
     * @return void
     */
    public function testDecodeBooleanAndNull()
    {
        $jsonEncodeValue = $this->utilEncodingService->decodeJson('true');

        $this->assertEquals(true, $jsonEncodeValue);

        $jsonEncodeValue = $this->utilEncodingService->decodeJson('false');

        $this->assertEquals(false, $jsonEncodeValue);

        $jsonEncodeValue = $this->utilEncodingService->decodeJson('null');

        $this->assertEquals(null, $jsonEncodeValue);
    }
}
