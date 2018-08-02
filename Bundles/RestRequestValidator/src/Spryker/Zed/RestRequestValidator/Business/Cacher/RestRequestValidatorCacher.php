<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Cacher;

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
     * @return void
     */
    public function store(array $validatorConfig): void
    {
        file_put_contents($this->cacheFile, Yaml::dump($validatorConfig));
    }
}
