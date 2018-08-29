<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Saver;

use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapterInterface;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapterInterface;

class RestRequestValidatorSaver implements RestRequestValidatorSaverInterface
{
    /**
     * @var string
     */
    protected $cacheFile;

    /**
     * @var \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapterInterface
     */
    protected $filesystem;

    /**
     * @var \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapterInterface
     */
    protected $yaml;

    /**
     * @param \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapterInterface $filesystem
     * @param \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapterInterface $yaml
     * @param string $cacheFile
     */
    public function __construct(
        RestRequestValidatorToFilesystemAdapterInterface $filesystem,
        RestRequestValidatorToYamlAdapterInterface $yaml,
        string $cacheFile
    ) {
        $this->filesystem = $filesystem;
        $this->yaml = $yaml;
        $this->cacheFile = $cacheFile;
    }

    /**
     * @param array $validatorConfig
     *
     * @return void
     */
    public function store(array $validatorConfig): void
    {
        $this->filesystem->dumpFile($this->cacheFile, $this->yaml->dump($validatorConfig));
    }
}
