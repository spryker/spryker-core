<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Processor;

use Generated\Shared\Transfer\UserTransfer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class CurrentUserDataRequestLogProcessor implements CurrentUserDataRequestLogProcessorInterface
{
    /**
     * @var string
     */
    protected const RECORD_KEY_EXTRA = 'extra';

    /**
     * @var string
     */
    protected const RECORD_KEY_REQUEST = 'request';

    /**
     * @see \Spryker\Zed\User\Business\Model\User::createUserKey()
     *
     * @var string
     */
    protected const SESSION_KEY_USER = 'user:currentUser';

    /**
     * @var string
     */
    protected const RECORD_KEY_USERNAME = 'username';

    /**
     * @var string
     */
    protected const RECORD_KEY_USER_UUID = 'user_uuid';

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack|null
     */
    protected ?RequestStack $requestStack;

    /**
     * @param \Symfony\Component\HttpFoundation\RequestStack|null $requestStack
     */
    public function __construct(?RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public function __invoke(array $data): array
    {
        $currentRequestData = $this->getCurrentRequestData();

        if (!$currentRequestData) {
            return $data;
        }

        if (isset($data[static::RECORD_KEY_EXTRA][static::RECORD_KEY_REQUEST])) {
            $data[static::RECORD_KEY_EXTRA][static::RECORD_KEY_REQUEST] = array_merge(
                $data[static::RECORD_KEY_EXTRA][static::RECORD_KEY_REQUEST],
                $currentRequestData,
            );

            return $data;
        }

        $data[static::RECORD_KEY_EXTRA][static::RECORD_KEY_REQUEST] = $currentRequestData;

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    public function getCurrentRequestData(): array
    {
        $currentRequestData = [];

        if (!$this->requestStack) {
            return $currentRequestData;
        }

        $currentRequest = $this->requestStack->getCurrentRequest();

        if (!$currentRequest || !$currentRequest->hasSession()) {
            return $currentRequestData;
        }

        $userTransfer = $this->findUser($currentRequest);
        if ($userTransfer) {
            $currentRequestData[static::RECORD_KEY_USERNAME] = $userTransfer->getUsername();
            $currentRequestData[static::RECORD_KEY_USER_UUID] = $userTransfer->getUuid();
        }

        return $currentRequestData;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    protected function findUser(Request $request): ?UserTransfer
    {
        return $request->getSession()->get(static::SESSION_KEY_USER);
    }
}
