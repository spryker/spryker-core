<?php


namespace Spryker\Zed\CmsBlock\Dependency\Facade;


interface CmsBlockToGlossaryFacadeInterface
{

    /**
     * @param array $keys
     *
     * @return bool
     */
    public function deleteTranslationsByFkKeys(array $keys);

    /**
     * @param array $keys
     *
     * @return bool
     */
    public function deleteKeys(array $keys);

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey($keyName);

}