<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\NavigationNodeTransfer;
use Spryker\Zed\NavigationGui\Communication\Form\NavigationNodeFormType;
use Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToLocaleInterface;
use Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToNavigationInterface;

class NavigationNodeFormDataProvider
{

    /**
     * @var \Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToNavigationInterface
     */
    protected $navigationFacade;

    /**
     * @var \Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToNavigationInterface $navigationFacade
     * @param \Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToLocaleInterface $localeFacade
     */
    public function __construct(NavigationGuiToNavigationInterface $navigationFacade, NavigationGuiToLocaleInterface $localeFacade)
    {
        $this->navigationFacade = $navigationFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int|null $idNavigationNode
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    public function getData($idNavigationNode = null)
    {
        $navigationNodeTransfer = new NavigationNodeTransfer();
        $navigationNodeTransfer = $this->setTranslationFields($navigationNodeTransfer);

        if ($idNavigationNode) {
            $navigationNodeTransfer->setIdNavigationNode($idNavigationNode);
            $navigationNodeTransfer = $this->navigationFacade->findNavigationNode($navigationNodeTransfer);
        }

        return $navigationNodeTransfer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            NavigationNodeFormType::OPTION_NODE_TYPE_OPTIONS => $this->getNodeTypeOptions(),
            NavigationNodeFormType::OPTION_NODE_TYPE_OPTION_ATTRIBUTES => $this->getNodeTypeOptionAttributes(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    protected function setTranslationFields(NavigationNodeTransfer $navigationNodeTransfer)
    {
        $availableLocales = $this->localeFacade->getLocaleCollection();

        foreach ($availableLocales as $localeTransfer) {
            $navigationNodeLocalizedAttributesTransfer = new NavigationNodeLocalizedAttributesTransfer();
            $navigationNodeLocalizedAttributesTransfer->setFkLocale($localeTransfer->getIdLocale());

            $navigationNodeTransfer->addNavigationNodeLocalizedAttribute($navigationNodeLocalizedAttributesTransfer);
        }

        return $navigationNodeTransfer;
    }

    /**
     * @return array
     */
    protected function getNodeTypeOptions()
    {
        return [
            'Category' => 'category',
            'CMS page' => 'cms',
            'Link' => 'link',
            'External URL' => 'external_url',
        ];
    }

    /**
     * @return array
     */
    protected function getNodeTypeOptionAttributes()
    {
        return [
            'Category' => ['data-url' => '/search-for-category'],
            'CMS page' => ['data-url' => '/search-for-cms'],
            'Link' => [],
            'External URL' => [],
        ];
    }

}
