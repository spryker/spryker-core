<?php

namespace Spryker\Client\Rbac;

use Generated\Shared\Transfer\RbacRequestTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Rbac\RbacFactory getFactory()
 */
class RbacClient extends AbstractClient implements RbacClientInterface
{
    /**
     * @param string $right
     * @param array $options
     *
     * @return bool
     */
    public function can($right, array $options)
    {
        $rbacRequestTransfer = new RbacRequestTransfer();
        $rbacRequestTransfer->setRight($right);
        $rbacRequestTransfer->setOptions($options);


        return $this->getFactory()
            ->createZedStub()
            ->can($rbacRequestTransfer);
    }
}