<?php

namespace SprykerFeature\Zed\SearchPage\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Shared\SearchPage\Dependency\PageElementInterface;

/**
 * @method SearchPageDependencyContainer getDependencyContainer()
 */
class SearchPageFacade extends AbstractFacade
{

    /**
     * @param PageElementInterface $pageElement
     *
     * @return int
     */
    public function createPageElement(PageElementInterface $pageElement)
    {
        return $this->getDependencyContainer()
            ->createPageElementWriter()
            ->createPageElement($pageElement)
        ;
    }

    /**
     * @param PageElementInterface $pageElement
     *
     * @return int
     */
    public function updatePageElement(PageElementInterface $pageElement)
    {
        return $this->getDependencyContainer()
            ->createPageElementWriter()
            ->createPageElement($pageElement)
        ;
    }

    /**
     * @param PageElementInterface $pageElement
     *
     * @return int
     */
    public function deletePageElement(PageElementInterface $pageElement)
    {
        return $this->getDependencyContainer()
            ->createPageElementWriter()
            ->createPageElement($pageElement)
        ;
    }

    /**
     * @param int $idPageElement
     * @param bool $isElementActive
     *
     * @return bool
     */
    public function switchActiveState($idPageElement, $isElementActive)
    {
        return $this->getDependencyContainer()
            ->createPageElementWriter()
            ->switchActiveState($idPageElement, $isElementActive)
        ;
    }

    public function installDocumentAttributes()
    {
        $this->getDependencyContainer()
            ->getDocumentAttributeInstaller()
            ->install()
        ;
    }

    public function installTemplates()
    {
        $this->getDependencyContainer()
            ->getTemplateInstaller()
            ->install()
        ;
    }
}
