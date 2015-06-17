<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Client\Communication\Plugin\Fixture;

require_once __DIR__ . '/GoodTransfer.php';

use Generated\Shared\Transfer\GoodTransfer;
use SprykerFeature\Shared\Foo\Client\NotTransferTransferObject;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractClientController;

class ClientController extends AbstractClientController
{
    public function __construct()
    {

    }

    public function fooAction()
    {
        return "foo";
    }

    public function barAction(FooTransfer $foo)
    {
        return $foo;
    }

    public function twoTransferParametersAction(FooTransfer $foo, FooTransfer $bar)
    {
        return $foo;
    }

    public function tooManyParametersAction(FooTransfer $foo, $bar, $baz)
    {
        return $foo;
    }

    public function withoutTypehintAction($foo)
    {
        return $foo;
    }

    public function notSharedTransferNamespaceAction(FooTransfer $foo)
    {
        return $foo;
    }

    public function notTransferTransferNamespaceAction(NotTransferTransferObject $foo)
    {
        return $foo;
    }

    public function goodAction(GoodTransfer $foo)
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
