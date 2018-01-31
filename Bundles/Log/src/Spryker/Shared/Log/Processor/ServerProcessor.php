<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log\Processor;

use Symfony\Component\HttpFoundation\Request;

class ServerProcessor implements ProcessorInterface
{
    const EXTRA = 'server';

    const URL = 'url';
    const IS_HTTPS = 'is_https';
    const HOST_NAME = 'hostname';
    const USER_AGENT = 'user_agent';
    const USER_IP = 'user_ip';
    const REQUEST_METHOD = 'request_method';
    const REFERER = 'referer';
    const RECORD_EXTRA = 'extra';

    /**
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record)
    {
        $record[static::RECORD_EXTRA][static::EXTRA] = $this->getData();

        return $record;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $request = Request::createFromGlobals();

        return [
            static::URL => $request->getUri(),
            static::IS_HTTPS => $request->isSecure(),
            static::HOST_NAME => $request->getHost(),
            static::USER_AGENT => $request->server->get('HTTP_USER_AGENT', null),
            static::USER_IP => $request->server->get('REMOTE_ADDR', null),
            static::REQUEST_METHOD => $request->server->get('REQUEST_METHOD', 'cli'),
            static::REFERER => $request->server->get('HTTP_REFERER', null),
        ];
    }
}
