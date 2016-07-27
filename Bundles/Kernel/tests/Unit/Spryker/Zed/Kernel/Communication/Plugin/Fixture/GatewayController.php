<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Kernel\Communication\Plugin\Fixture;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use StdClass;

class GatewayController extends AbstractGatewayController
{

    /**
     * @return string
     */
    public function badAction()
    {
        return 'bad';
    }

    /**
     * @param \Spryker\Shared\Transfer\TransferInterface $foo
     *
     * @return \Spryker\Shared\Transfer\TransferInterface
     */
    public function goodAction(TransferInterface $foo)
    {
        return $foo;
    }

    /**
     * @param \Generated\Shared\Transfer\CartTransfer $foo
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function good2Action(QuoteTransfer $foo)
    {
        return $foo;
    }

    /**
     * @param \Spryker\Shared\Transfer\TransferInterface $foo
     * @param \Spryker\Shared\Transfer\TransferInterface $bar
     *
     * @return \Spryker\Shared\Transfer\TransferInterface
     */
    public function twoTransferParametersAction(TransferInterface $foo, TransferInterface $bar)
    {
        if ($bar) {
        }

        return $foo;
    }

    /**
     * @param \Spryker\Shared\Transfer\TransferInterface $foo
     * @param mixed $bar
     * @param mixed $baz
     *
     * @return \Spryker\Shared\Transfer\TransferInterface
     */
    public function tooManyParametersAction(TransferInterface $foo, $bar, $baz)
    {
        if ($bar && $baz) {
        }

        return $foo;
    }

    /**
     * @param \StdClass $foo
     *
     * @return \StdClass
     */
    public function notTransferAction(StdClass $foo)
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
