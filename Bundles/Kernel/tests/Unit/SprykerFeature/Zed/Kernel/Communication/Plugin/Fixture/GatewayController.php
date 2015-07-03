<?php

namespace Unit\SprykerFeature\Zed\Kernel\Communication\Plugin\Fixture;

use Generated\Shared\Cart\CartInterface;
use SprykerEngine\Shared\Transfer\TransferInterface;
use SprykerFeature\Zed\Kernel\Communication\Controller\AbstractGatewayController;

class GatewayController extends AbstractGatewayController
{

    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function badAction()
    {
        return 'bad';
    }

    /**
     * @param TransferInterface $foo
     *
     * @return TransferInterface
     */
    public function goodAction(TransferInterface $foo)
    {
        return $foo;
    }

    /**
     * @param TransferInterface $foo
     *
     * @return TransferInterface
     */
    public function good2Action(CartInterface $foo)
    {
        return $foo;
    }

    /**
     * @param TransferInterface $foo
     * @param TransferInterface $bar
     *
     * @return TransferInterface
     */
    public function twoTransferParametersAction(TransferInterface $foo, TransferInterface $bar)
    {
        return $foo;
    }

    /**
     * @param TransferInterface $foo
     * @param mixed $bar
     * @param mixed $baz
     *
     * @return TransferInterface
     */
    public function tooManyParametersAction(TransferInterface $foo, $bar, $baz)
    {
        return $foo;
    }

    /**
     * @param \StdClass $foo
     *
     * @return \StdClass
     */
    public function notTransferAction(\StdClass $foo)
    {
        return $foo;
    }

    /**
     * @param mixed $foo
     *
     * @return mixed
     */
    public function noClassParameterAction($foo)
    {
        return $foo;
    }

    public function transformMessageAction()
    {
        $this->addMessage('message', ['key' => 'value']);
        $this->addErrorMessage('error', ['errorKey' => 'errorValue']);
        $this->setSuccess(false);
    }

}
