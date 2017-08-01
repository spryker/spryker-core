<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedRequest\Business\Model;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\ZedRequest\Client\RequestInterface;
use Spryker\Shared\ZedRequest\ZedRequestConstants;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class Repeater implements RepeaterInterface
{

    /**
     * @var bool
     */
    protected $isRepeatInProgress = false;

    /**
     * @param string|null $mvc
     *
     * @return string
     */
    public function getRepeatData($mvc = null)
    {
        $this->isRepeatInProgress = true;
        if ($mvc !== null) {
            return $this->getFlashInFile('last_yves_request_' . $mvc . '.log');
        } else {
            return $this->getFlashInFile('last_yves_request.log');
        }
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
            'params' => $transferObject->toArray(false),
        ];

        $mvc = sprintf(
            '%s_%s_%s',
            $httpRequest->attributes->get('module'),
            $httpRequest->attributes->get('controller'),
            $httpRequest->attributes->get('action')
        );

        $this->setFlashInFile($repeatData, 'last_yves_request_' . $mvc . '.log');
        $this->setFlashInFile($repeatData, 'last_yves_request.log');
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
            mkdir($directory);
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
        return APPLICATION_ROOT_DIR . '/data/' . Store::getInstance()->getStoreName() . '/logs/ZED/' . $fileName;
    }

}
