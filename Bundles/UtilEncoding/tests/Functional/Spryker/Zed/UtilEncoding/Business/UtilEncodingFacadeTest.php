<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\UtilEncoding\Business;

use Codeception\TestCase\Test;
use Spryker\Zed\UtilEncoding\Business\UtilEncodingFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group UtilEncoding
 * @group Business
 * @group UtilEncodingFacadeTest
 */
class UtilEncodingFacadeTest extends Test
{

    const JSON_ENCODED_VALUE = '{"1":"one","2":"two"}';

    /**
     * @var array
     */
    protected $jsonData = [1 => 'one', 2 => 'two'];

    /**
     * @var \Spryker\Zed\UtilEncoding\Business\UtilEncodingFacade
     */
    protected $utilEncodingFacade;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->utilEncodingFacade = new UtilEncodingFacade();
    }

    /**
     * @return void
     */
    public function testEncodeJsonShouldReturnJsonEncodedValue()
    {
        $jsonEncodeValue = $this->utilEncodingFacade->encodeJson($this->jsonData);

        $this->assertEquals(self::JSON_ENCODED_VALUE, $jsonEncodeValue);
    }

    /**
     * @return void
     */
    public function testDecodeJsonShouldReturnAssocArray()
    {
        $jsonDecodeValue = $this->utilEncodingFacade->decodeJson(self::JSON_ENCODED_VALUE, true);

        $this->assertEquals($this->jsonData, $jsonDecodeValue);
    }

    /**
     * @return void
     */
    public function testDecodeJsonWhenAssocFlagIsOffShouldReturnStdObject()
    {
        $jsonDecodeValue = $this->utilEncodingFacade->decodeJson(self::JSON_ENCODED_VALUE);

        $this->assertEquals((object)$this->jsonData, $jsonDecodeValue);
    }

}
