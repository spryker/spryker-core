<?php
namespace SprykerFeature\Zed\Checkout\Business\Model\Workflow\Definition;

use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerFeature\Shared\ZedRequest\Client\RequestInterface;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Checkout\Business\Model\Workflow\Context;
use SprykerEngine\Zed\Kernel\Business\ModelResult;
use SprykerFeature\Zed\Library\Workflow\ContextInterface;
use SprykerFeature\Zed\Library\Workflow\Definition;

abstract class AbstractDefinition extends Definition
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var OrderTransfer
     */
    private $transferOrder;

    /**
     * @var RequestInterface
     */
    private $transferRequest;

    /**
     * @param OrderTransfer $transferOrder
     * @param RequestInterface $transferRequest
     * @param FactoryInterface $factory
     */
    public function __construct(OrderTransfer $transferOrder, RequestInterface $transferRequest, FactoryInterface $factory)
    {
        $this->factory = $factory;
        $this->transferOrder = clone $transferOrder;
        $this->transferRequest = $transferRequest;
        $this->init();
    }

    public function init()
    {
        $this->context = $this->factory->createModelWorkflowContext(array());
        $this->context->setTransferOrder($this->transferOrder);
        $this->context->setTransferRequest($this->transferRequest);
    }

    /**
     * @return ContextInterface
     */
    protected function buildContext()
    {
        return $this->context;
    }

    /**
     * @return \SprykerFeature\Zed\Library\Workflow\TaskInvokerInterface
     */
    protected function getTaskInvoker()
    {
        return $this->factory->createModelWorkflowTaskInvoker();
    }

    /**
     * @param ContextInterface $context
     * @return ModelResult
     */
    protected function getSuccessResultFromContext(ContextInterface $context)
    {
        /* @var Context $context */
        assert($context instanceof Context);
        $result = new ModelResult($context->getOrderEntity());
        $result->setTransfer($context->getTransferOrder());

        return $result;
    }

}
