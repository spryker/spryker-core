<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi\Processor\Resolver;

use Exception;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToStoreStorageClientInterface;
use Symfony\Component\HttpFoundation\Request;

class StoreResolver implements StoreResolverInterface
{
    /**
     * @var string
     */
    protected const PARAMETER_STORE_NAME = '_store';

    /**
     * @var string
     */
    protected const HEADER_STORE_NAME = 'Store';

    /**
     * @var \Spryker\Glue\StoresApi\Dependency\Client\StoresApiToStoreStorageClientInterface
     */
    protected StoresApiToStoreStorageClientInterface $storeStorageClient;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected Request $request;

    /**
     * @param \Spryker\Glue\StoresApi\Dependency\Client\StoresApiToStoreStorageClientInterface $storeStorageClient
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(
        StoresApiToStoreStorageClientInterface $storeStorageClient,
        Request $request
    ) {
        $this->storeStorageClient = $storeStorageClient;
        $this->request = $request;
    }

    /**
     * @throws \Exception
     *
     * @return string
     */
    public function resolveStoreName(): string
    {
        $storeName = $this->findStoreName();
        if ($storeName) {
            return $storeName;
        }

        if (defined('APPLICATION_STORE')) {
            return APPLICATION_STORE;
        }

        $storeNames = $this->storeStorageClient->getStoreNames();
        $defaultStoreName = current($storeNames);

        if (!$defaultStoreName) {
            throw new Exception('Cannot resolve store.');
        }

        return $defaultStoreName;
    }

    /**
     * @return string|null
     */
    protected function findStoreName(): ?string
    {
        if ($this->request->query->has(static::PARAMETER_STORE_NAME)) {
            return (string)$this->request->query->get(static::PARAMETER_STORE_NAME);
        }

        if ($this->request->headers->has(static::HEADER_STORE_NAME)) {
            return (string)$this->request->headers->get(static::HEADER_STORE_NAME);
        }

        return null;
    }
}
