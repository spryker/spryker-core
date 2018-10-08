<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log\Processor;

use Spryker\Service\UtilNetwork\UtilNetworkService;
use Spryker\Shared\Log\Sanitizer\SanitizerInterface;
use Symfony\Component\HttpFoundation\Request;

class RequestProcessor implements ProcessorInterface
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

        $request = $this->findRequest((array)$record[static::RECORD_CONTEXT]);
        if ($request && $request->getSession() !== null) {
            $this->addSessionId($request, $fields);
            $this->addUsername($request, $fields);
        }

        return $fields;
    }

    /**
     * @return array
     */
    protected function getRequestParams()
    {
        $request = Request::createFromGlobals();
        $all = array_merge(
            $request->request->all(),
            $request->query->all()
        );

        return $this->sanitizer->sanitize($all);
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
     * @return \Symfony\Component\HttpFoundation\Request|null
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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $fields
     *
     * @return array
     */
    protected function addSessionId(Request $request, array $fields)
    {
        $sessionId = $request->getSession()->getId();
        $fields[static::SESSION_ID] = $sessionId;

        return $fields;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $fields
     *
     * @return array
     */
    protected function addUsername(Request $request, array $fields)
    {
        $userTransfer = $this->findUser($request);
        if ($userTransfer) {
            $fields[static::USERNAME] = $userTransfer->getUsername();
        }

        return $fields;
    }
}
