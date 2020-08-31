<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\Generator;

use Symfony\Component\Filesystem\Filesystem;

class IndexMapCleaner implements IndexMapCleanerInterface
{
    /**
     * @var string
     */
    protected $directory;

    /**
     * @param string $directory
     */
    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    /**
     * @return void
     */
    public function cleanDirectory()
    {
        if (is_dir($this->directory)) {
            $fileSystem = new Filesystem();
            $fileSystem->remove($this->directory);
        }
    }
}
