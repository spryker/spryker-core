<?php


namespace Spryker\Zed\CmsBlock\Dependency\Facade;


use Generated\Shared\Transfer\KeyTranslationTransfer;
use Spryker\Zed\Glossary\Business\GlossaryFacadeInterface;

class CmsBlockToGlossaryFacadeBridge implements CmsBlockToGlossaryFacadeInterface
{

    /**
     * @var GlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param GlossaryFacadeInterface $glossaryFacade
     */
    public function __construct($glossaryFacade)
    {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param array $keys
     *
     * @return bool
     */
    public function deleteTranslationsByFkKeys(array $keys)
    {
        return $this->glossaryFacade->deleteTranslationsByFkKeys($keys);
    }

    /**
     * @param array $keys
     *
     * @return bool
     */
    public function deleteKeys(array $keys)
    {
        return $this->glossaryFacade->deleteKeys($keys);
    }

    /**
     * @param string $keyName
     * @return bool
     */
    public function hasKey($keyName)
    {
        return $this->glossaryFacade->hasKey($keyName);
    }

    /**
     * @param KeyTranslationTransfer $keyTranslationTransfer
     *
     * @return bool
     */
    public function saveGlossaryKeyTranslations(KeyTranslationTransfer $keyTranslationTransfer)
    {
        return $this->glossaryFacade->saveGlossaryKeyTranslations($keyTranslationTransfer);
    }

}