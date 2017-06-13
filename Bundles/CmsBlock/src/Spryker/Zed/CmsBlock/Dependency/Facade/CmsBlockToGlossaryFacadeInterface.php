<?php


namespace Spryker\Zed\CmsBlock\Dependency\Facade;


use Generated\Shared\Transfer\KeyTranslationTransfer;

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

    /**
     * @param KeyTranslationTransfer $keyTranslationTransfer
     *
     * @return bool
     */
    public function saveGlossaryKeyTranslations(KeyTranslationTransfer $keyTranslationTransfer);

}