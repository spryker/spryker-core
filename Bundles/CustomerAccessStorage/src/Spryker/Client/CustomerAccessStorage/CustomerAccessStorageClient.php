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
     * @param string $contentType
     *
     * @return bool
     */
    public function canUnauthenticatedCustomerAccessContentType($contentType)
    {
        return $this->getFactory()
            ->createCustomerAccessStorageReader()
            ->canUnauthenticatedCustomerAccessContentType($contentType);
    }
}
