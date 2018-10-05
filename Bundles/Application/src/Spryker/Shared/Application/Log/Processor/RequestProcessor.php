<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application\Log\Processor;

use Spryker\Service\UtilNetwork\UtilNetworkService;
use Spryker\Shared\Log\Sanitizer\SanitizerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Use `RequestProcessorPlugin` from Log module instead.
 */
class RequestProcessor
{
    public const EXTRA = 'request';
    public const CONTEXT_KEY = 'request';

    public const REQUEST_PARAMS = 'request_params';
    public const REQUEST_ID = 'requestId';
    public const SESSION_ID = 'sessionId';
    public const USERNAME = 'username';
    public const REQUEST_TYPE = 'type';

    public const RECORD_CONTEXT = 'context';
    public const SESSION_KEY_USER = 'user:currentUser';
    public const RECORD_EXTRA = 'extra';

    /**
     * @var \Spryker\Shared\Log\Sanitizer\SanitizerInterface
     */
    protected $sanitizer;

    /**
     * @param \Spryker\Shared\Log\Sanitizer\SanitizerInterface $sanitizer
     */
    public function __construct(SanitizerInterface $sanitizer)
    {
        $this->sanitizer = $sanitizer;
    }

    /**
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record)
    {
        $record[self::RECORD_EXTRA][static::EXTRA] = $this->getData($record);

        if (isset($record[self::RECORD_CONTEXT][static::CONTEXT_KEY])) {
            unset($record[self::RECORD_CONTEXT][static::CONTEXT_KEY]);
        }

        return $record;
    }

    /**
     * @param array $record
     *
     * @return array
     */
    public function getData(array $record)
    {
        $utilNetworkService = new UtilNetworkService();
        $fields = [
            static::REQUEST_ID => $utilNetworkService->getRequestId(),
            static::REQUEST_TYPE => $this->getSapi(),
            static::REQUEST_PARAMS => $this->getRequestParams(),
        ];

        $request = $this->findRequest((array)$record[self::RECORD_CONTEXT]);
        if ($request && $request->getSession() !== null) {
            $sessionId = $request->getSession()->getId();
            $fields[static::SESSION_ID] = $sessionId;

            $userTransfer = $this->findUser($request);
            if ($userTransfer) {
                $fields[static::USERNAME] = $userTransfer->getUsername();
            }
        }

        return $fields;
    }

    /**
     * @return array
     */
    protected function getRequestParams()
    {
        return $this->sanitizer->sanitize($_REQUEST);
    }

    /**
     * @return string
     */
    protected function getSapi()
    {
        return (PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg') ? 'CLI' : 'WEB';
    }

    /**
     * @param array $context
     *
     * @return bool|\Symfony\Component\HttpFoundation\Request|null
     */
    protected function findRequest(array $context)
    {
        if (!empty($context[static::CONTEXT_KEY])) {
            return $context[static::CONTEXT_KEY];
        }

        return null;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    protected function findUser(Request $request)
    {
        return $request->getSession()->get(static::SESSION_KEY_USER);
    }
}
