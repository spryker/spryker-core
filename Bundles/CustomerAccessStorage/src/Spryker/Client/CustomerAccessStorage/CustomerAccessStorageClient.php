<?php

namespace Spryker\Client\CustomerAccessStorage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CustomerAccessStorage\CustomerAccessStorageFactory getFactory()
 */
class CustomerAccessStorageClient extends AbstractClient implements CustomerAccessStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $content
     *
     * @return bool
     */
    public function canUnauthenticatedCustomerAccessContent($content)
    {
        return $this->getFactory()->createCustomerAccessStorageReader()->findUnauthenticatedCustomerAccess();
    }
}
