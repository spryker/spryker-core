<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Dependency\Service;

class DataImportToFlysystemServiceBridge implements DataImportToFlysystemServiceInterface
{
    /**
     * @var \Spryker\Service\Flysystem\FlysystemServiceInterface
     */
    protected $flysystemService;

    /**
     * @param \Spryker\Service\Flysystem\FlysystemServiceInterface $flysystemService
     */
    public function __construct($flysystemService)
    {
        $this->flysystemService = $flysystemService;
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return bool
     */
    public function has(string $filesystemName, string $path): bool
    {
        return $this->flysystemService->has($filesystemName, $path);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return mixed
     */
    public function readStream(string $filesystemName, string $path)
    {
        return $this->flysystemService->readStream($filesystemName, $path);
    }
}
