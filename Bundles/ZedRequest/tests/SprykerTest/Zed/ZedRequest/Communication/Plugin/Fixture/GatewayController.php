<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedRequest\Communication\Plugin\Fixture;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use stdClass;

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
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $foo
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function goodAction(TransferInterface $foo)
    {
        return $foo;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $foo
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $bar
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function twoTransferParametersAction(TransferInterface $foo, TransferInterface $bar)
    {
        if ($bar) {
        }

        return $foo;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $foo
     * @param mixed $bar
     * @param mixed $baz
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function tooManyParametersAction(TransferInterface $foo, $bar, $baz)
    {
        if ($bar && $baz) {
        }

        return $foo;
    }

    /**
     * @param \stdClass $foo
     *
     * @return \stdClass
     */
    public function notTransferAction(stdClass $foo)
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
