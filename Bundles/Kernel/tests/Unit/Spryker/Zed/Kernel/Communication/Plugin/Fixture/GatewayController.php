<?php

namespace Unit\Spryker\Zed\Kernel\Communication\Plugin\Fixture;

use Generated\Shared\Transfer\CartTransfer;
use Spryker\Shared\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

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
     * @param CartTransfer $foo
     *
     * @return CartTransfer
     */
    public function good2Action(CartTransfer $foo)
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
        if ($bar) {}

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
        if ($bar && $baz) {}

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

    /**
     * @return void
     */
    public function transformMessageAction()
    {
        $this->addInfoMessage('info');
        $this->addErrorMessage('error');
        $this->addSuccessMessage('success');
        $this->setSuccess(false);
    }

}
