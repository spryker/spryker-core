<?php

namespace Unit\SprykerFeature\Zed\Queue\Business\Worker;

use Codeception\TestCase\Test;
use Unit\SprykerFeature\Zed\Queue\Mock\MockQueueConnection;
use SprykerFeature\Zed\Queue\Business\Provider\TaskProvider;
use SprykerFeature\Zed\Queue\Business\Worker\TaskWorker;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Queue
 * @group Business
 * @group QueueFacadeTest
 */
class TaskWorkerTest extends Test
{

    /**
     * @var TaskWorker
     */
    protected $taskWorker;

    protected function setUp()
    {
        parent::setUp();

        $mockTaskPlugin = $this->getMockTaskPlugin();
        $taskProvider = new TaskProvider([$mockTaskPlugin]);
        $this->taskWorker = new TaskWorker(
            new MockQueueConnection(),
            $taskProvider,
            'test_queue'
        );
    }

    public function testTaskWorker()
    {
        $this->taskWorker->work();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockTaskPlugin()
    {
        $mockBuilder = $this->getMockBuilder(
            'Unit\SprykerFeature\Zed\Queue\Mock\MockTaskPlugin'
        )->setConstructorArgs(['task_name', 'test_queue']);
        $mockTaskPlugin = $mockBuilder->getMock();
        $mockTaskPlugin
            ->expects($this->once())
            ->method('run');
        $mockTaskPlugin
            ->expects($this->any())
            ->method('getQueueName')
            ->will($this->returnValue('test_queue'));

        return $mockTaskPlugin;
    }
}
