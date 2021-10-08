<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model\Provider;

use League\Flysystem\FilesystemOperator;
use Spryker\Service\Flysystem\Exception\FilesystemNotFoundException;

class FilesystemProvider implements FilesystemProviderInterface
{
    /**
     * @var array<\League\Flysystem\FilesystemOperator>
     */
    protected $filesystemCollection;

    /**
     * @param array<\League\Flysystem\FilesystemOperator> $filesystemCollection
     */
    public function __construct(array $filesystemCollection)
    {
        $this->filesystemCollection = $filesystemCollection;
    }

    /**
     * @param string $name
     *
     * @throws \Spryker\Service\Flysystem\Exception\FilesystemNotFoundException
     *
     * @return \League\Flysystem\FilesystemOperator
     */
    public function getFilesystemByName($name): FilesystemOperator
    {
        if (!array_key_exists($name, $this->filesystemCollection)) {
            throw new FilesystemNotFoundException(
                sprintf('Flysystem "%s" was not found', $name)
            );
        }

        return $this->filesystemCollection[$name];
    }

    /**
     * @return array<\League\Flysystem\FilesystemOperator>
     */
    public function getFilesystemCollection(): array
    {
        return $this->filesystemCollection;
    }
}
