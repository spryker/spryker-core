<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Application\Log\Processor;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\Application\Log\Processor\RequestProcessor;
use Spryker\Shared\Log\Sanitizer\Sanitizer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Application
 * @group Log
 * @group Processor
 * @group RequestProcessorTest
 * Add your own group annotations below this line
 */
class RequestProcessorTest extends Unit
{
    /**
     * @return void
     */
    public function testInvokeShouldAddRequestInformationToRecordsExtra()
    {
        $sanitizer = new Sanitizer([], '***');
        $processor = new RequestProcessor($sanitizer);
        $record = [RequestProcessor::RECORD_EXTRA => [], RequestProcessor::RECORD_CONTEXT => []];
        $result = $processor($record);

        $this->assertArrayHasKey(RequestProcessor::EXTRA, $result[RequestProcessor::RECORD_EXTRA]);
    }

    /**
     * @return void
     */
    public function testWhenRequestInContextSessionIdShouldBeAdded()
    {
        $sanitizer = new Sanitizer([], '***');
        $processor = new RequestProcessor($sanitizer);
        $record = [
            RequestProcessor::RECORD_EXTRA => [],
            RequestProcessor::RECORD_CONTEXT => [
                RequestProcessor::CONTEXT_KEY => $this->getRequestMockWithSession(),
            ],
        ];
        $result = $processor($record);

        $this->assertArrayHasKey(RequestProcessor::SESSION_ID, $result[RequestProcessor::RECORD_EXTRA][RequestProcessor::EXTRA]);
    }

    /**
     * @return void
     */
    public function testWhenRequestInContextAndUserInSessionUsernameShouldBeAdded()
    {
        $sanitizer = new Sanitizer([], '***');
        $processor = new RequestProcessor($sanitizer);
        $record = [
            RequestProcessor::RECORD_EXTRA => [],
            RequestProcessor::RECORD_CONTEXT => [
                RequestProcessor::CONTEXT_KEY => $this->getRequestMockWithUser(),
            ],
        ];
        $result = $processor($record);

        $this->assertArrayHasKey(RequestProcessor::USERNAME, $result[RequestProcessor::RECORD_EXTRA][RequestProcessor::EXTRA]);
    }

    /**
     * @return void
     */
    public function testWhenRequestInContextItMustBeRemovedAfterProcessing()
    {
        $sanitizer = new Sanitizer([], '***');
        $processor = new RequestProcessor($sanitizer);
        $record = [
            RequestProcessor::RECORD_EXTRA => [],
            RequestProcessor::RECORD_CONTEXT => [
                RequestProcessor::CONTEXT_KEY => $this->getRequestMockWithSession(),
            ],
        ];
        $result = $processor($record);

        $this->assertArrayNotHasKey(RequestProcessor::CONTEXT_KEY, $result[RequestProcessor::RECORD_CONTEXT]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequestMockWithSession()
    {
        $request = Request::createFromGlobals();
        $session = new Session(new MockArraySessionStorage());
        $request->setSession($session);

        return $request;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequestMockWithUser()
    {
        $request = Request::createFromGlobals();
        $session = new Session(new MockArraySessionStorage());
        $userTransfer = new UserTransfer();
        $userTransfer->setUsername('username');
        $session->set(RequestProcessor::SESSION_KEY_USER, $userTransfer);
        $request->setSession($session);

        return $request;
    }
}
