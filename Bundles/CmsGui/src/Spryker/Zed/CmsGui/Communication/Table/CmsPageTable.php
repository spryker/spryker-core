<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Table;

use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\CmsGui\CmsGuiConfig;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToLocaleInterface;
use Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CmsPageTable extends AbstractTable
{
    protected const BUTTON_LABEL_EDIT = 'Edit';

    /**
     * @var \Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\CmsGui\CmsGuiConfig
     */
    protected $cmsGuiConfig;

    /**
     * @var \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface
     */
    protected $cmsFacade;

    /**
     * @var \Spryker\Zed\CmsGui\Dependency\Plugin\CmsPageTableExpanderPluginInterface[]
     */
    protected $cmsPageTableExpanderPlugins;

    /**
     * @param \Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToLocaleInterface $localeFacade
     * @param \Spryker\Zed\CmsGui\CmsGuiConfig $cmsGuiConfig
     * @param \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface $cmsFacade
     * @param \Spryker\Zed\CmsGui\Dependency\Plugin\CmsPageTableExpanderPluginInterface[] $cmsPageTableExpanderPlugins
     */
    public function __construct(
        CmsGuiToCmsQueryContainerInterface $cmsQueryContainer,
        CmsGuiToLocaleInterface $localeFacade,
        CmsGuiConfig $cmsGuiConfig,
        CmsGuiToCmsInterface $cmsFacade,
        array $cmsPageTableExpanderPlugins
    ) {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->cmsGuiConfig = $cmsGuiConfig;
        $this->cmsFacade = $cmsFacade;
        $this->cmsPageTableExpanderPlugins = $cmsPageTableExpanderPlugins;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $this->setHeaders($config);
        $this->setRawColumns($config);
        $this->setSortableFields($config);
        $this->setSearchableFields($config);
        $this->setDefaultSortField($config);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $localeTransfer = $this->localeFacade->getCurrentLocale();

        $cmsPageAttributesTransfer = new CmsPageAttributesTransfer();
        $cmsPageAttributesTransfer->setLocaleName($localeTransfer->getLocaleName());

        $urlPrefix = $this->cmsFacade->getPageUrlPrefix($cmsPageAttributesTransfer);
        $query = $this->cmsQueryContainer->queryLocalizedPagesWithTemplates();

        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $item) {
            $results[] = $this->mapResults($item, $urlPrefix);
        }

        return $results;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function buildUrlList(array $item)
    {
        $cmsUrls = $this->extractUrls($item);

        return implode('<br />', $cmsUrls);
    }

    /**
     * @param array $item
     * @param string $urlPrefix
     *
     * @return array
     */
    protected function buildLinks(array $item, $urlPrefix)
    {
        $buttons = [];

        $buttons[] = $this->createPublishButton($item);
        $buttons[] = $this->createViewButtonGroup($item, $urlPrefix);
        $buttons[] = $this->createEditButtonGroup($item);
        $buttons[] = $this->createCmsStateChangeButton($item);

        return $buttons;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function createPublishButton(array $item)
    {
        return $this->generateCreateButton(
            Url::generate('/cms-gui/version-page/publish', [
                CmsPageTableConstants::VERSION_PAGE_URL_PARAM_ID_CMS_PAGE => $item[CmsPageTableConstants::COL_ID_CMS_PAGE],
                CmsPageTableConstants::VERSION_PAGE_URL_PARAM_REDIRECT_URL => '/cms-gui/list-page/index',
            ]),
            'Publish',
            [
                'icon' => 'fa-upload',
                'class' => 'safe-submit',
            ]
        );
    }

    /**
     * @param array $item
     * @param string $urlPrefix
     *
     * @return string
     */
    protected function createViewButtonGroup(array $item, $urlPrefix)
    {
        $groupItems = $this->getViewButtonGroupItems($item, $urlPrefix);

        if (count($groupItems) === 0) {
            return '';
        }

        return $this->generateButtonGroup($groupItems, 'View');
    }

    /**
     * @param array $item
     * @param string $urlPrefix
     *
     * @return array
     */
    protected function getViewButtonGroupItems(array $item, $urlPrefix)
    {
        $groupItems = $this->getViewButtonGroupPermanentItems($item);

        if ($this->isDraft($item)) {
            return $groupItems;
        }

        $groupItems[] = $this->createViewButtonItem($item);
        $groupItems[] = $this->createViewInShopButtonItem($item, $urlPrefix);

        if (!$this->hasMultipleVersions($item)) {
            return $groupItems;
        }

        $groupItems[] = $this->createVersionHistoryButton($item);

        return $groupItems;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function getViewButtonGroupPermanentItems(array $item)
    {
        $viewButtonGroupPermanentItems = [];

        foreach ($this->cmsPageTableExpanderPlugins as $cmsPageTableExpanderPlugin) {
            $viewButtonGroupPermanentItems = array_merge($viewButtonGroupPermanentItems, $cmsPageTableExpanderPlugin->getViewButtonGroupPermanentItems($item));
        }

        return $viewButtonGroupPermanentItems;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function createViewButtonItem(array $item)
    {
        return $this->createButtonGroupItem(
            'In Zed',
            Url::generate('/cms-gui/view-page/index', [
                CmsPageTableConstants::LIST_PAGE_URL_PARAM_ID_CMS_PAGE => $item[CmsPageTableConstants::COL_ID_CMS_PAGE],
            ])
        );
    }

    /**
     * @param array $item
     * @param string $urlPrefix
     *
     * @return string|array
     */
    protected function createViewInShopButtonItem(array $item, $urlPrefix)
    {
        if ($this->isDraft($item)) {
            return '';
        }

        $yvesHost = $this->cmsGuiConfig->findYvesHost();
        if ($yvesHost === null) {
            return '';
        }

        $currentLocaleUrl = $this->findCurrentLocaleUrl($item, $urlPrefix);
        if ($currentLocaleUrl === null) {
            return '';
        }

        $cmsPageUrlInYves = $yvesHost . $currentLocaleUrl;

        return $this->createButtonGroupItem(
            'In Shop',
            $cmsPageUrlInYves,
            false,
            [
                'target' => '_blank',
            ]
        );
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function createVersionHistoryButton(array $item)
    {
        return $this->createButtonGroupItem(
            'Version History',
            Url::generate('/cms-gui/version-page/history', [
                CmsPageTableConstants::VERSION_PAGE_URL_PARAM_ID_CMS_PAGE => $item[CmsPageTableConstants::COL_ID_CMS_PAGE],
            ]),
            true
        );
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function createEditButtonGroup(array $item): string
    {
        $cmsGlossaryTransfer = $this->cmsFacade
            ->findPageGlossaryAttributes($item[CmsPageTableConstants::COL_ID_CMS_PAGE]);

        $buttonOptions = [
            'class' => 'btn-edit',
            'icon' => 'fa-pencil-square-o',
        ];

        if ($cmsGlossaryTransfer && $cmsGlossaryTransfer->getGlossaryAttributes()->count() === 0) {
            return $this->generateButton(
                Url::generate('/cms-gui/edit-page/index', [
                    CmsPageTableConstants::EDIT_PAGE_URL_PARAM_ID_CMS_PAGE => $item[CmsPageTableConstants::COL_ID_CMS_PAGE],
                ]),
                static::BUTTON_LABEL_EDIT,
                $buttonOptions
            );
        }

        return $this->generateButtonGroup(
            [
                $this->createEditPageButtonItem($item),
                $this->createEditGlossaryButtonItem($item),
            ],
            static::BUTTON_LABEL_EDIT,
            $buttonOptions
        );
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function createEditPageButtonItem(array $item)
    {
        return $this->createButtonGroupItem(
            'Page',
            Url::generate('/cms-gui/edit-page/index', [
                CmsPageTableConstants::EDIT_PAGE_URL_PARAM_ID_CMS_PAGE => $item[CmsPageTableConstants::COL_ID_CMS_PAGE],
            ])
        );
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function createEditGlossaryButtonItem(array $item)
    {
        return $this->createButtonGroupItem(
            'Placeholders',
            Url::generate('/cms-gui/create-glossary/index', [
                CmsPageTableConstants::CREATE_GLOSSARY_URL_PARAM_ID_CMS_PAGE => $item[CmsPageTableConstants::COL_ID_CMS_PAGE],
            ])
        );
    }

    /**
     * @param array $item
     * @param string $urlPrefix
     *
     * @return string|null
     */
    protected function findCurrentLocaleUrl(array $item, $urlPrefix)
    {
        $cmsUrls = $this->extractUrls($item);
        foreach ($cmsUrls as $url) {
            if (preg_match('#^' . $urlPrefix . '#i', $url) > 0) {
                return $url;
            }
        }

        if (count($cmsUrls) > 0) {
            return $cmsUrls[0];
        }

        return null;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function createCmsStateChangeButton(array $item)
    {
        if ($this->isDraft($item)) {
            return '';
        }

        if ($item[CmsPageTableConstants::COL_IS_ACTIVE]) {
            return $this->generateRemoveButton(
                Url::generate(CmsPageTableConstants::URL_CMS_PAGE_DEACTIVATE, [
                    CmsPageTableConstants::EDIT_PAGE_URL_PARAM_ID_CMS_PAGE => $item[CmsPageTableConstants::COL_ID_CMS_PAGE],
                    CmsPageTableConstants::EDIT_PAGE_URL_PARAM_REDIRECT_URL => '/cms-gui/list-page/index',
                ]),
                'Deactivate'
            );
        }

        return $this->generateViewButton(
            Url::generate(CmsPageTableConstants::URL_CMS_PAGE_ACTIVATE, [
                CmsPageTableConstants::EDIT_PAGE_URL_PARAM_ID_CMS_PAGE => $item[CmsPageTableConstants::COL_ID_CMS_PAGE],
                CmsPageTableConstants::EDIT_PAGE_URL_PARAM_REDIRECT_URL => '/cms-gui/list-page/index',
            ]),
            'Activate'
        );
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function getActiveStatusLabel($item)
    {
        if (!$item[CmsPageTableConstants::COL_IS_ACTIVE]) {
            return $this->generateLabel('Inactive', 'label-danger');
        }

        return $this->generateLabel('Active', 'label-info');
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function getStatusLabel($item)
    {
        if ($item[CmsPageTableConstants::COL_CMS_VERSION_COUNT] > 0) {
            return $this->getActiveStatusLabel($item);
        }

        return $this->generateLabel('Unpublished', 'label-default');
    }

    /**
     * @param array $item
     *
     * @return string[]
     */
    protected function extractUrls(array $item)
    {
        return explode(',', $item[CmsPageTableConstants::COL_CMS_URLS]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setHeaders(TableConfiguration $config)
    {
        $config->setHeader([
            CmsPageTableConstants::COL_ID_CMS_PAGE => '#',
            CmsPageTableConstants::COL_NAME => 'Name',
            CmsPageTableConstants::COL_URL => 'Url',
            CmsPageTableConstants::COL_TEMPLATE => 'Template',
            CmsPageTableConstants::COL_STATUS => 'Status',
            CmsPageTableConstants::COL_STORE_RELATION => 'Stores',
            CmsPageTableConstants::ACTIONS => CmsPageTableConstants::ACTIONS,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setRawColumns(TableConfiguration $config)
    {
        $config->setRawColumns([
            CmsPageTableConstants::ACTIONS,
            CmsPageTableConstants::COL_URL,
            CmsPageTableConstants::COL_STATUS,
            CmsPageTableConstants::COL_STORE_RELATION,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setSortableFields(TableConfiguration $config)
    {
        $config->setSortable([
            CmsPageTableConstants::COL_ID_CMS_PAGE,
            CmsPageTableConstants::COL_TEMPLATE,
            CmsPageTableConstants::COL_NAME,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setSearchableFields(TableConfiguration $config)
    {
        $config->setSearchable([
            CmsPageTableConstants::COL_ID_CMS_PAGE,
            CmsPageTableConstants::COL_NAME,
            CmsPageTableConstants::COL_TEMPLATE,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setDefaultSortField(TableConfiguration $config)
    {
        $config->setDefaultSortField(CmsPageTableConstants::COL_ID_CMS_PAGE, CmsPageTableConstants::SORT_DESC);
    }

    /**
     * @param array $item
     * @param string $urlPrefix
     *
     * @return array
     */
    protected function mapResults(array $item, $urlPrefix)
    {
        $actions = implode(' ', $this->buildLinks($item, $urlPrefix));

        return [
            CmsPageTableConstants::COL_ID_CMS_PAGE => $item[CmsPageTableConstants::COL_ID_CMS_PAGE],
            CmsPageTableConstants::COL_NAME => $this->buildCmsPageName($item),
            CmsPageTableConstants::COL_URL => $this->buildUrlList($item),
            CmsPageTableConstants::COL_TEMPLATE => $item[CmsPageTableConstants::COL_TEMPLATE],
            CmsPageTableConstants::COL_STATUS => $this->getStatusLabel($item),
            CmsPageTableConstants::COL_STORE_RELATION => $this->getStoreNames($item[CmsPageTableConstants::COL_ID_CMS_PAGE]),
            CmsPageTableConstants::ACTIONS => $actions,
        ];
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function buildCmsPageName(array $item): string
    {
        $cmsNames = $this->extractNames($item);

        return reset($cmsNames);
    }

    /**
     * @param array $item
     *
     * @return string[]
     */
    protected function extractNames(array $item): array
    {
        return explode(',', $item[CmsPageTableConstants::COL_NAME]);
    }

    /**
     * @param array $item
     *
     * @return bool
     */
    protected function isDraft(array $item)
    {
        return $item[CmsPageTableConstants::COL_CMS_VERSION_COUNT] <= 0;
    }

    /**
     * @param array $item
     *
     * @return bool
     */
    protected function hasMultipleVersions(array $item)
    {
        return $item[CmsPageTableConstants::COL_CMS_VERSION_COUNT] > 1;
    }

    /**
     * @param int $idCmsPage
     *
     * @return string
     */
    protected function getStoreNames(int $idCmsPage): string
    {
        $cmsPageTransfer = $this->cmsFacade->findCmsPageById($idCmsPage);

        return $this->formatStoreNames($cmsPageTransfer->getStoreRelation());
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer|null $storeRelationTransfer
     *
     * @return string
     */
    protected function formatStoreNames(?StoreRelationTransfer $storeRelationTransfer): string
    {
        if (!$storeRelationTransfer) {
            return '';
        }

        $storeNames = [];

        foreach ($storeRelationTransfer->getStores() as $storeTransfer) {
            $storeNames[] = sprintf(
                '<span class="label label-info">%s</span>',
                $storeTransfer->getName()
            );
        }

        return implode(" ", $storeNames);
    }
}
