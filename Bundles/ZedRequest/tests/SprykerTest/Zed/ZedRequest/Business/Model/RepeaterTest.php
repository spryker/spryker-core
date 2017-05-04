<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedRequest\Business\Model;

use Codeception\Configuration;
use Codeception\TestCase\Test;
use Spryker\Shared\ZedRequest\Client\AbstractRequest;
use Spryker\Shared\ZedRequest\ZedRequestConstants;
use Spryker\Zed\ZedRequest\Business\Model\Repeater;
use Spryker\Zed\ZedRequest\ZedRequestConfig;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ZedRequest
 * @group Business
 * @group Model
 * @group RepeaterTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\ZedRequest\BusinessTester $tester
 */
class RepeaterTest extends Test
{

    const MODULE = 'module';
    const CONTROLLER = 'controller';
    const ACTION = 'action';

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->cleanupFixtureDirectory();

        $this->tester->setConfig(ZedRequestConstants::YVES_REQUEST_REPEAT_DATA_PATH, $this->getPathToYvesRequestRepeatData());
        $this->tester->setConfig(ZedRequestConstants::SET_REPEAT_DATA, true);
    }

    /**
     * @return void
     */
    private function cleanupFixtureDirectory()
    {
        $fixtureDirectory = $this->getPathToYvesRequestRepeatData();
        $filesystem = new Filesystem();
        if (is_dir($fixtureDirectory)) {
            $filesystem->remove($fixtureDirectory);
        }
    }

    /**
     * @return string
     */
    private function getPathToYvesRequestRepeatData()
    {
        $pathToYvesRequestRepeatData = Configuration::dataDir() . 'Fixtures' . DIRECTORY_SEPARATOR;

        return $pathToYvesRequestRepeatData;
    }

    /**
     * @return string
     */
    private function getDefaultFileName()
    {
        $defaultFileName = $this->getPathToYvesRequestRepeatData() . $this->getConfig()->getYvesRequestRepeatDataFileName();

        return $defaultFileName;
    }

    /**
     * @return \Spryker\Zed\ZedRequest\ZedRequestConfig
     */
    private function getConfig()
    {
        return new ZedRequestConfig();
    }

    /**
     * The MVC file name is the default file name + `_module_controller_action`.
     *
     * @return string
     */
    private function getMvcFileName()
    {
        $mvc = $this->getMvcPortion();

        $mvcFileName = $this->getPathToYvesRequestRepeatData() . $this->getConfig()->getYvesRequestRepeatDataFileName($mvc);

        return $mvcFileName;
    }

    /**
     * @return string
     */
    private function getMvcPortion()
    {
        $mvc = implode('_', [
            static::MODULE,
            static::CONTROLLER,
            static::ACTION,
        ]);

        return $mvc;
    }

    /**
     * @return void
     */
    public function testSetRepeatedDataWritesDataToFiles()
    {
        $requestMock = $this->getRequestMock();
        $httpRequest = $this->getHttpRequest();

        $repeater = new Repeater();
        $repeater->setRepeatData($requestMock, $httpRequest);

        $this->assertFileExists($this->getDefaultFileName());
        $this->assertFileExists($this->getMvcFileName());
    }

    /**
     * @return void
     */
    public function testGetRepeatedDataReturnsArray()
    {
        $repeater = new Repeater();

        $this->assertInternalType('array', $repeater->getRepeatData());
        $this->assertInternalType('array', $repeater->getRepeatData($this->getMvcPortion()));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\ZedRequest\Client\AbstractRequest
     */
    private function getRequestMock()
    {
        $requestMockBuilder = $this->getMockBuilder(AbstractRequest::class);

        return $requestMockBuilder->getMock();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    private function getHttpRequest()
    {
        $httpRequest = new Request();
        $httpRequest->attributes->set(static::MODULE, static::MODULE);
        $httpRequest->attributes->set(static::CONTROLLER, static::CONTROLLER);
        $httpRequest->attributes->set(static::ACTION, static::ACTION);

        return $httpRequest;
    }

}
