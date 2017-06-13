<?php


namespace Spryker\Zed\CmsBlock\Dependency\Facade;


use Spryker\Zed\Locale\Business\LocaleFacadeInterface;

class CmsBlockToLocaleFacadeBridge implements CmsBlockToLocaleFacadeInterface
{
    /**
     * @var LocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param LocaleFacadeInterface $localeFacade
     */
    public function __construct($localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return array
     */
    public function getAvailableLocales()
    {
        return $this->localeFacade->getAvailableLocales();
    }

}