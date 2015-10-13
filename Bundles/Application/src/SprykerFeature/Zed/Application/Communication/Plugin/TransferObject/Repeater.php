<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Communication\Plugin\TransferObject;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Shared\Application\ApplicationConfig;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\Library\Log;
use SprykerFeature\Shared\ZedRequest\Client\RequestInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class Repeater extends AbstractPlugin
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
        if (!is_null($mvc)) {
            return Log::getFlashInFile('last_yves_request_' . $mvc . '.log');
        } else {
            return Log::getFlashInFile('last_yves_request.log');
        }
    }

    /**
     * @param RequestInterface $transferObject
     * @param HttpRequest $httpRequest
     */
    public function setRepeatData(RequestInterface $transferObject, HttpRequest $httpRequest)
    {
        if ($this->isRepeatInProgress) {
            return;
        }

        if (Config::get(ApplicationConfig::SET_REPEAT_DATA, false)) {
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

        Log::setFlashInFile($repeatData, 'last_yves_request_' . $mvc . '.log');
        Log::setFlashInFile($repeatData, 'last_yves_request.log');
    }

}
