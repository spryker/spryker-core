<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedRequest\Helper;

use Codeception\Configuration;
use Codeception\Module;
use Codeception\TestInterface;
use Codeception\Util\Stub;
use Spryker\Shared\ZedRequest\Client\AbstractRequest;
use Spryker\Shared\ZedRequest\ZedRequestConstants;
use Spryker\Zed\ZedRequest\ZedRequestConfig;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

class RepeaterHelper extends Module
{
    const BUNDLE = 'module';
    const CONTROLLER = 'controller';
    const ACTION = 'action';

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test)
    {
        $this->cleanupFixtureDirectory();

        $this->setConfig(ZedRequestConstants::YVES_REQUEST_REPEAT_DATA_PATH, $this->getPathToYvesRequestRepeatData());
        $this->setConfig(ZedRequestConstants::SET_REPEAT_DATA, true);
    }

    /**
     * @param string $key
     * @param string|int|array|float|bool $value
     *
     * @return void
     */
    private function setConfig($key, $value)
    {
        $this->getConfigHelper()->setConfig($key, $value);
    }

    /**
     * @return \Codeception\Module|\SprykerTest\Shared\Testify\Helper\ConfigHelper
     */
    private function getConfigHelper()
    {
        return $this->getModule('\\' . ConfigHelper::class);
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
    public function getDefaultFileName()
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
     * The BundleControllerAction file name is the default file name + `_bundle_controller_action`.
     *
     * @return string
     */
    public function getFileNameWithBundleControllerAction()
    {
        $bundleControllerAction = $this->getBundleControllerAction();

        $mvcFileName = $this->getPathToYvesRequestRepeatData() . $this->getConfig()->getYvesRequestRepeatDataFileName($bundleControllerAction);

        return $mvcFileName;
    }

    /**
     * @return string
     */
    public function getBundleControllerAction()
    {
        $mvc = implode('_', [
            static::BUNDLE,
            static::CONTROLLER,
            static::ACTION,
        ]);

        return $mvc;
    }

    /**
     * @return object|\Spryker\Shared\ZedRequest\Client\AbstractRequest
     */
    public function getTransferRequest()
    {
        return Stub::make(AbstractRequest::class);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getHttpRequest()
    {
        $httpRequest = new Request();
        $httpRequest->attributes->set(static::BUNDLE, static::BUNDLE);
        $httpRequest->attributes->set(static::CONTROLLER, static::CONTROLLER);
        $httpRequest->attributes->set(static::ACTION, static::ACTION);

        return $httpRequest;
    }
}
