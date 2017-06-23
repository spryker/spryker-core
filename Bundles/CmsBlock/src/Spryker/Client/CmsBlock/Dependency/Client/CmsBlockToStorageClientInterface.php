<?php


namespace Spryker\Client\CmsBlock;


interface CmsBlockToStorageClientInterface
{
    /**
     * @param array $keys
     *
     * @return array
     */
    public function getMulti($keys);

}