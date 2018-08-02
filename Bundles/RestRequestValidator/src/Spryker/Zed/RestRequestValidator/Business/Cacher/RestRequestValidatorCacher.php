<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Cacher;

use Spryker\Zed\RestRequestValidator\Business\Exception\SchemaCouldNotBeWritten;
use Symfony\Component\Yaml\Yaml;

class RestRequestValidatorCacher implements RestRequestValidatorCacherInterface
{
    /**
     * @var string
     */
    protected $cacheFile;

    /**
     * @param string $cacheFile
     */
    public function __construct(string $cacheFile)
    {
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
        if (!$this->checkPathExistsOrCreate($this->cacheFile)) {
            throw new SchemaCouldNotBeWritten(
                'Could not write schema validation cache. Please check the paths are writable.'
            );
        }
        file_put_contents($this->cacheFile, Yaml::dump($validatorConfig));
    }

    /**
     * @param string $cacheFile
     *
     * @return bool
     */
    protected function checkPathExistsOrCreate(string $cacheFile): bool
    {
        return true;
    }
}
