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
 * @deprecated Use `RequestProcessorPlugin`s from Log module instead.
 */
class RequestProcessor
{
    /**
     * @var string
     */
    public const EXTRA = 'request';

    /**
     * @var string
     */
    public const CONTEXT_KEY = 'request';

    /**
     * @var string
     */
    public const REQUEST_PARAMS = 'request_params';

    /**
     * @var string
     */
    public const REQUEST_ID = 'requestId';

    /**
     * @var string
     */
    public const SESSION_ID = 'sessionId';

    /**
     * @var string
     */
    public const USERNAME = 'username';

    /**
     * @var string
     */
    public const REQUEST_TYPE = 'type';

    /**
     * @var string
     */
    public const RECORD_CONTEXT = 'context';

    /**
     * @var string
     */
    public const SESSION_KEY_USER = 'user:currentUser';

    /**
     * @var string
     */
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
        $record[static::RECORD_EXTRA][static::EXTRA] = $this->getData($record);

        if (isset($record[static::RECORD_CONTEXT][static::CONTEXT_KEY])) {
            unset($record[static::RECORD_CONTEXT][static::CONTEXT_KEY]);
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

        /** @var \Symfony\Component\HttpFoundation\Request|null $request */
        $request = $this->findRequest((array)$record[static::RECORD_CONTEXT]);
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
     * @return \Symfony\Component\HttpFoundation\Request|bool|null
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
