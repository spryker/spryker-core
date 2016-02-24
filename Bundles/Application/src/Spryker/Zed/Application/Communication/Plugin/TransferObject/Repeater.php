<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Plugin\TransferObject;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Library\Log;
use Spryker\Shared\ZedRequest\Client\RequestInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * @method \Spryker\Zed\Application\Business\ApplicationFacade getFacade()
 * @method \Spryker\Zed\Application\Communication\ApplicationCommunicationFactory getFactory()
 */
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
        if ($mvc !== null) {
            return Log::getFlashInFile('last_yves_request_' . $mvc . '.log');
        } else {
            return Log::getFlashInFile('last_yves_request.log');
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

        if (Config::get(ApplicationConstants::SET_REPEAT_DATA, false) === false) {
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
