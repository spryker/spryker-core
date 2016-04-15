<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Propel\Business\Model;

use Spryker\Zed\Propel\Business\Exception\ConfigDirectoryNotCreatedException;
use Spryker\Zed\Propel\Business\Exception\ConfigFileNotCreatedException;
use Spryker\Zed\Propel\Business\Exception\ConfigMissingPropertyException;
use Spryker\Zed\Propel\Business\Model\PropelConfigConverterJson;

/**
 * @group Spryker
 * @group Zed
 * @group Propel
 * @group Business
 * @group PropelConfigConverterJson
 */
class PropelConfigConverterJsonTest extends \PHPUnit_Framework_TestCase
{

    const FILE_NAME = 'propel.json';

    /**
     * @var string
     */
    protected $fixtureDirectory;

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
     * @return void
     */
    public function testInitialization()
    {
        $config = ['paths' => ['phpConfDir' => 'cat face']];
        $propelConfigConverterJson = new PropelConfigConverterJson($config);

        $this->assertInstanceOf(PropelConfigConverterJson::class, $propelConfigConverterJson);
    }

    /**
     * @return void
     */
    public function testInitializationThrowsExceptionWhenNeededDataIsMissing()
    {
        $this->setExpectedException(ConfigMissingPropertyException::class);
        new PropelConfigConverterJson([]);
    }

    /**
     * @return void
     */
    public function testInitializationCreatesTargetDirectory()
    {
        $this->assertFalse(is_dir($this->getFixtureDirectory()));

        $config = ['paths' => ['phpConfDir' => $this->getFixtureDirectory()]];
        new PropelConfigConverterJson($config);

        $this->assertTrue(is_dir($this->getFixtureDirectory()));
    }

    public function testConvertConfig()
    {
        $this->assertFalse(file_exists($this->fixtureDirectory . self::FILE_NAME));

        $config = ['paths' => ['phpConfDir' => $this->getFixtureDirectory()]];
        $propelConfigConverterJson = new PropelConfigConverterJson($config);
        $propelConfigConverterJson->convertConfig();

        $this->assertTrue(file_exists($this->fixtureDirectory . self::FILE_NAME));
    }

    public function testConvertConfigThrowsExceptionIfFileNotCreated()
    {
        $this->assertFalse(file_exists($this->fixtureDirectory . self::FILE_NAME));

        $config = ['paths' => ['phpConfDir' => $this->getFixtureDirectory()]];

        $propelConfigConverterJsonMock = $this->getMock(PropelConfigConverterJson::class, ['writeToFile'], [$config]);
        $propelConfigConverterJsonMock->expects($this->once())->method('writeToFile');

        $this->setExpectedException(ConfigFileNotCreatedException::class);
        $propelConfigConverterJsonMock->convertConfig();
    }

}
