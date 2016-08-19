<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Mail\Business;

use Spryker\Zed\Mail\Business\InclusionHandler;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Mail
 * @group Business
 * @group InclusionHandlerTest
 */
class InclusionHandlerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Spryker\Zed\Mail\Business\InclusionHandlerInterface
     */
    protected $inclusionHandler;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->inclusionHandler = new InclusionHandler();
    }

    /**
     * @return void
     */
    public function testInferType()
    {
        $textFilePath = __DIR__ . '/testfile.txt';

        $this->assertEquals('text/plain', $this->inclusionHandler->guessType($textFilePath));
    }

    /**
     * @return void
     */
    public function testGetFilename()
    {
        $textFilePath = __DIR__ . '/testfile.txt';

        $this->assertEquals('testfile.txt', $this->inclusionHandler->getFilename($textFilePath));
    }

    /**
     * @return void
     */
    public function testBase64Encoding()
    {
        $textFilePath = __DIR__ . '/testfile.txt';

        $this->assertEquals('VGVzdCBGaWxlIENvbnRlbnQ=', $this->inclusionHandler->encodeBase64($textFilePath));
    }

}
