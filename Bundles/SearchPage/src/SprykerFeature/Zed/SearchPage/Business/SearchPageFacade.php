<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
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

    /**
     * @param MessengerInterface $messenger
     */
    public function installDocumentAttributes(MessengerInterface $messenger)
    {
        $this->getDependencyContainer()
            ->createDocumentAttributeInstaller($messenger)
            ->install()
        ;
    }

    /**
     * @param MessengerInterface $messenger
     */
    public function installTemplates(MessengerInterface $messenger)
    {
        $this->getDependencyContainer()
            ->createTemplateInstaller($messenger)
            ->install()
        ;
    }

    /**
     * @param array $configRaw
     * @param LocaleTransfer $localeDto
     *
     * @return array
     */
    public function processSearchPageConfig(array $configRaw, LocaleTransfer $localeDto)
    {
        return $this->getDependencyContainer()
            ->createSearchPageConfigProcessor()
            ->processSearchPageConfig($configRaw, $localeDto)
        ;
    }

}
