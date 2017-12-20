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
        $hasRight = $this->hasRight($right);

        if ($hasRight === false) {
            return false;
        }

        $rbacRequestTransfer = new RbacRequestTransfer();
        $rbacRequestTransfer->setRight($right);
        $rbacRequestTransfer->setOptions($options);


        return $this->getFactory()
            ->createZedStub()
            ->getIsAllowed($rbacRequestTransfer);
    }

    /**
     * Specification:
     * - KV lookup
     *
     * @param string $right
     *
     * @return bool
     */
    public function hasRight($right)
    {
        return true;
    }

    public function getIsAllowed($right, array $options)
    {

    }
}