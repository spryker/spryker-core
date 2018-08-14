<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Saver;

use Spryker\Zed\RestRequestValidator\Business\Exception\PathDoesNotExistException;
use Spryker\Zed\RestRequestValidator\Business\Exception\SchemaCouldNotBeWritten;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use Throwable;

class RestRequestValidatorSaver implements RestRequestValidatorSaverInterface
{
    /**
     * @var string
     */
    protected $cacheFile;

    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * @param \Symfony\Component\Filesystem\Filesystem $filesystem
     * @param string $cacheFile
     */
    public function __construct(Filesystem $filesystem, string $cacheFile)
    {
        $this->filesystem = $filesystem;
        $this->cacheFile = $cacheFile;
    }

    /**
     * @param array $validatorConfig
     *
     * @throws \Spryker\Zed\RestRequestValidator\Business\Exception\SchemaCouldNotBeWritten
     *
     * @return void
     */
    public function store(array $validatorConfig): void
    {
        if (!$this->checkPathExistsOrCreate($this->cacheFile)
            || !file_put_contents($this->cacheFile, Yaml::dump($validatorConfig))
        ) {
            throw new SchemaCouldNotBeWritten(
                'Could not write schema validation cache. Please check the paths are writable.'
            );
        }
    }

    /**
     * @param string $cacheFile
     *
     * @throws \Spryker\Zed\RestRequestValidator\Business\Exception\PathDoesNotExistException
     *
     * @return bool
     */
    protected function checkPathExistsOrCreate(string $cacheFile): bool
    {
        $directoryName = dirname($cacheFile);
        if ($this->filesystem->exists($directoryName)) {
            return true;
        }

        try {
            $this->filesystem->mkdir($directoryName);
        } catch (Throwable $throwable) {
            throw new PathDoesNotExistException('Cache storage path does not exist and could not be created.');
        }

        return true;
    }
}
