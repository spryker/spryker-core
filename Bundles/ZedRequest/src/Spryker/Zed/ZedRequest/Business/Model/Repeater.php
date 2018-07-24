<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedRequest\Business\Model;

use Spryker\Shared\Config\Config;
use Spryker\Shared\ZedRequest\Client\RequestInterface;
use Spryker\Shared\ZedRequest\ZedRequestConstants;
use Spryker\Zed\Kernel\BundleConfigResolverAwareTrait;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * @method \Spryker\Zed\ZedRequest\ZedRequestConfig getConfig()
 */
class Repeater implements RepeaterInterface
{
    /**
     * This is a hack to get around a bad design which uses the singleton pattern.
     *
     * We need the configuration in this class to let customers control the path
     * and file name for last yves request log data.
     */
    use BundleConfigResolverAwareTrait;

    /**
     * @var bool
     */
    protected $isRepeatInProgress = false;

    /**
     * @param string|null $moduleControllerAction
     *
     * @return array
     */
    public function getRepeatData($moduleControllerAction = null)
    {
        $this->isRepeatInProgress = true;
        if ($moduleControllerAction !== null) {
            return $this->getFlashInFile($this->getConfig()->getYvesRequestRepeatDataFileName($moduleControllerAction));
        }

        return $this->getFlashInFile($this->getConfig()->getYvesRequestRepeatDataFileName());
    }

    /**
     * @param \Spryker\Shared\ZedRequest\Client\RequestInterface $transferObject
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     *
     * @return void
     */
    public function setRepeatData(RequestInterface $transferObject, HttpRequest $httpRequest)
    {
        if ($this->isRepeatInProgress) {
            return;
        }

        if (Config::get(ZedRequestConstants::SET_REPEAT_DATA, false) === false) {
            return;
        }

        $repeatData = [
            'module' => $httpRequest->attributes->get('module'),
            'controller' => $httpRequest->attributes->get('controller'),
            'action' => $httpRequest->attributes->get('action'),
            'params' => $transferObject->toArray(),
        ];

        $moduleControllerAction = sprintf(
            '%s_%s_%s',
            $httpRequest->attributes->get('module'),
            $httpRequest->attributes->get('controller'),
            $httpRequest->attributes->get('action')
        );

        $this->setFlashInFile($repeatData, $this->getConfig()->getYvesRequestRepeatDataFileName($moduleControllerAction));
        $this->setFlashInFile($repeatData, $this->getConfig()->getYvesRequestRepeatDataFileName());
    }

    /**
     * @param array $repeatData
     * @param string $fileName
     *
     * @return void
     */
    protected function setFlashInFile(array $repeatData, $fileName)
    {
        $filePath = $this->getFilePath($fileName);
        $string = serialize($repeatData);

        $directory = dirname($filePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents($filePath, $string);
    }

    /**
     * @param string $fileName
     *
     * @return array
     */
    protected function getFlashInFile($fileName)
    {
        $filePath = $this->getFilePath($fileName);
        if (!file_exists($filePath)) {
            return [];
        }

        $content = file_get_contents($filePath);
        if (empty($content)) {
            return [];
        }

        return unserialize($content);
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    protected function getFilePath($fileName)
    {
        return $this->getConfig()->getPathToYvesRequestRepeatData($fileName);
    }
}
