<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\Cache\Storage;

use Spryker\Shared\Kernel\ClassResolver\Cache\AbstractStorage;
use Spryker\Shared\Library\DataDirectory;
use Spryker\Shared\Library\Json;

class File extends AbstractStorage
{

    /**
     * @param array $data
     *
     * @return void
     */
    public function persist(array $data)
    {
        if (!$this->isModified()) {
            return;
        }

        try {
            //TODO check this http://php.net/manual/en/function.file-put-contents.php#82934
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
