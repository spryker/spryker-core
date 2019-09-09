<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\UrlStorage\Storage;

use Generated\Shared\Transfer\UrlRedirectStorageTransfer;
use Spryker\Client\UrlStorage\Dependency\Client\UrlStorageToStorageInterface;
use Spryker\Client\UrlStorage\KeyBuilder\UrlRedirectStorageKeyBuilderInterface;
use Spryker\Client\UrlStorage\Mapper\UrlRedirectStorageMapperInterface;

class UrlRedirectStorageReader implements UrlRedirectStorageReaderInterface
{
    /**
     * @var \Spryker\Client\UrlStorage\Dependency\Client\UrlStorageToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\UrlStorage\KeyBuilder\UrlRedirectStorageKeyBuilderInterface
     */
    protected $redirectStorageKeyBuilder;

    /**
     * @var \Spryker\Client\UrlStorage\Mapper\UrlRedirectStorageMapperInterface
     */
    protected $urlRedirectStorageMapper;

    /**
     * @param \Spryker\Client\UrlStorage\Dependency\Client\UrlStorageToStorageInterface $storageClient
     * @param \Spryker\Client\UrlStorage\KeyBuilder\UrlRedirectStorageKeyBuilderInterface $redirectStorageKeyBuilder
     * @param \Spryker\Client\UrlStorage\Mapper\UrlRedirectStorageMapperInterface $urlRedirectStorageMapper
     */
    public function __construct(
        UrlStorageToStorageInterface $storageClient,
        UrlRedirectStorageKeyBuilderInterface $redirectStorageKeyBuilder,
        UrlRedirectStorageMapperInterface $urlRedirectStorageMapper
    ) {
        $this->storageClient = $storageClient;
        $this->redirectStorageKeyBuilder = $redirectStorageKeyBuilder;
        $this->urlRedirectStorageMapper = $urlRedirectStorageMapper;
    }

    /**
     * @param int $idRedirectUrl
     *
     * @return \Generated\Shared\Transfer\UrlRedirectStorageTransfer|null
     */
    public function findUrlRedirectStorageById(int $idRedirectUrl): ?UrlRedirectStorageTransfer
    {
        $data = $this->storageClient->get($this->redirectStorageKeyBuilder->generateKey($idRedirectUrl));

        if (!$data) {
            return null;
        }

        return $this->urlRedirectStorageMapper->mapStorageDataToUrlRedirectStorageTransfer($data, new UrlRedirectStorageTransfer());
    }
}
