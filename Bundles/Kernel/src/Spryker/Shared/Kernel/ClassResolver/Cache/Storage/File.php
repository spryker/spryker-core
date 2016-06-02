<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\Cache\Storage;

use Spryker\Shared\Kernel\ClassResolver\Cache\StorageInterface;
use Spryker\Shared\Library\DataDirectory;
use Spryker\Shared\Library\Error\ErrorLogger;

class File implements StorageInterface
{

    /**
     * @param array $data
     *
     * @return void
     */
    public function persist(array $data)
    {
        try {
            $string = var_export($data, true);

            file_put_contents(
                $this->getCacheFilename(),
                '<?php return ' . $string . ';',
                LOCK_EX | LOCK_NB
            );
        } catch (\Exception $exception) {
            ErrorLogger::log($exception);
        }
    }

    /**
     * @return array
     */
    public function getData()
    {
        try {
            $cache = include $this->getCacheFilename();

            return $cache ?: [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * @return string
     */
    protected function getCacheFilename()
    {
        return DataDirectory::getLocalStoreSpecificPath('cache/autoloader').'/unresolvable.php';
    }

}
