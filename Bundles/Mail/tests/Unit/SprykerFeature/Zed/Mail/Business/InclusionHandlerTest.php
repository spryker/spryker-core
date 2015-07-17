<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Mail\Business;

use SprykerFeature\Zed\Mail\Business\InclusionHandler;
use SprykerFeature\Zed\Mail\Business\InclusionHandlerInterface;

/**
 * @group InclusionHandler
 */
class InclusionHandlerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var InclusionHandlerInterface
     */
    protected $inclusionHandler;

    protected function setUp()
    {
        parent::setUp();
        $this->inclusionHandler = new InclusionHandler();
    }

    public function testInferType()
    {
        $textFilePath = __DIR__ . '/testfile.txt';

        $this->assertEquals('text/plain', $this->inclusionHandler->guessType($textFilePath));
    }

    public function testGetFilename()
    {
        $textFilePath = __DIR__ . '/testfile.txt';

        $this->assertEquals('testfile.txt', $this->inclusionHandler->getFilename($textFilePath));
    }

    public function testBase64Encoding()
    {
        $textFilePath = __DIR__ . '/testfile.txt';

        $this->assertEquals('VGVzdCBGaWxlIENvbnRlbnQ=', $this->inclusionHandler->encodeBase64($textFilePath));
    }

}
