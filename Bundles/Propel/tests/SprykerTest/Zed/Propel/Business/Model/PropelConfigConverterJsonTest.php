<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business\Model;

use Codeception\Test\Unit;
use Spryker\Zed\Propel\Business\Exception\ConfigFileNotCreatedException;
use Spryker\Zed\Propel\Business\Exception\ConfigMissingPropertyException;
use Spryker\Zed\Propel\Business\Model\PropelConfigConverterJson;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Propel
 * @group Business
 * @group Model
 * @group PropelConfigConverterJsonTest
 * Add your own group annotations below this line
 */
class PropelConfigConverterJsonTest extends Unit
{
    public const FILE_NAME = 'propel.json';

    /**
     * @var string
     */
    protected $fixtureDirectory;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->fixtureDirectory = $this->getFixtureDirectory();

        $fileName = $this->fixtureDirectory . self::FILE_NAME;

        if (file_exists($fileName)) {
            unlink($fileName);
        }

        if (is_dir($this->fixtureDirectory)) {
            rmdir($this->fixtureDirectory);
        }
    }

    /**
     * @return string
     */
    protected function getFixtureDirectory()
    {
        return __DIR__ . '/Fixtures/Config/';
    }

    /**
     * @return array
     */
    protected function getTestConfiguration()
    {
        return [
            'paths' => [
                'phpConfDir' => $this->getFixtureDirectory(),
            ],
            'database' => [
                'connections' => [
                    'default' => '',
                ],
            ],
        ];
    }

    /**
     * @return void
     */
    public function testInitialization()
    {
        $propelConfigConverterJson = new PropelConfigConverterJson($this->getTestConfiguration());

        $this->assertInstanceOf(PropelConfigConverterJson::class, $propelConfigConverterJson);
    }

    /**
     * @return void
     */
    public function testInitializationThrowsExceptionWhenDataIsMissing()
    {
        $this->expectException(ConfigMissingPropertyException::class);
        new PropelConfigConverterJson([]);
    }

    /**
     * @return void
     */
    public function testInitializationCreatesTargetDirectory()
    {
        $this->assertFalse(is_dir($this->getFixtureDirectory()));

        new PropelConfigConverterJson($this->getTestConfiguration());

        $this->assertTrue(is_dir($this->getFixtureDirectory()));
    }

    /**
     * @return void
     */
    public function testConvertConfig()
    {
        $this->assertFalse(file_exists($this->fixtureDirectory . self::FILE_NAME));

        $propelConfigConverterJson = new PropelConfigConverterJson($this->getTestConfiguration());
        $propelConfigConverterJson->convertConfig();

        $this->assertTrue(file_exists($this->fixtureDirectory . self::FILE_NAME));
    }

    /**
     * @return void
     */
    public function testConvertConfigThrowsExceptionIfFileNotCreated()
    {
        $this->assertFalse(file_exists($this->fixtureDirectory . self::FILE_NAME));

        $propelConfigConverterJsonMock = $this->getPropelConfigConvertJsonMock();

        $this->expectException(ConfigFileNotCreatedException::class);
        $propelConfigConverterJsonMock->convertConfig();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Propel\Business\Model\PropelConfigConverterInterface
     */
    protected function getPropelConfigConvertJsonMock()
    {
        $propelConfigConverterJsonMock = $this->getMockBuilder(PropelConfigConverterJson::class)
            ->setMethods(['writeToFile'])
            ->setConstructorArgs([$this->getTestConfiguration()])
            ->getMock();

        $propelConfigConverterJsonMock
            ->expects($this->once())
            ->method('writeToFile');

        return $propelConfigConverterJsonMock;
    }
}
