<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\Cache\Storage;

use Spryker\Shared\Kernel\ClassResolver\Cache\StorageInterface;
use Spryker\Shared\Library\DataDirectory;
use Spryker\Shared\Library\Json;

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
            file_put_contents($this->getCacheFilename(), Json::encode(
                $data
            ));
        }
        catch (\Exception $e) {

        }
    }

    /**
     * @return array
     */
    public function getData()
    {
        try {
            $json = file_get_contents(
                $this->getCacheFilename()
            );

            $data = (array)Json::decode($json);

            return $data ?: [];
        }
        catch (\Exception $e) {
            return [];
        }
    }

    /**
     * @return string
     */
    protected function getCacheFilename()
    {
        return DataDirectory::getLocalStoreSpecificPath('cache/autoloader').'/unresolvable.json';
    }

}
