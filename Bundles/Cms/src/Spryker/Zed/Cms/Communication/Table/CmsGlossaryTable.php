<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Table;

use Orm\Zed\Cms\Persistence\Map\SpyCmsGlossaryKeyMappingTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Cms\Persistence\CmsQueryContainer;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CmsGlossaryTable extends AbstractTable
{
    /**
     * @var string
     */
    public const ACTIONS = 'Actions';

    /**
     * @var string
     */
    public const REQUEST_ID_MAPPING = 'id-mapping';

    /**
     * @var string
     */
    public const URL_CMS_GLOSSARY_EDIT = '/cms/glossary/edit';

    /**
     * @var string
     */
    public const URL_CMS_GLOSSARY_DELETE = '/cms/glossary/delete';

    /**
     * @var \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    protected $glossaryQuery;

    /**
     * @var array<string>
     */
    protected $placeholders;

    /**
     * @var int
     */
    protected $idPage;

    /**
     * @var array<string, mixed>
     */
    protected $searchArray;

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery $glossaryQuery
     * @param int $idPage
     * @param array<string> $placeholders
     * @param array<string, mixed> $searchArray
     */
    public function __construct(SpyCmsGlossaryKeyMappingQuery $glossaryQuery, int $idPage, array $placeholders = [], array $searchArray = [])
    {
        $this->glossaryQuery = $glossaryQuery;
        $this->idPage = $idPage;
        $this->placeholders = $placeholders;
        $this->searchArray = $searchArray;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            SpyCmsGlossaryKeyMappingTableMap::COL_ID_CMS_GLOSSARY_KEY_MAPPING => 'Id',
            SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER => 'Placeholder',
            CmsQueryContainer::KEY => 'Glossary Key',
            CmsQueryContainer::TRANS => 'Glossary Value',
            static::ACTIONS => static::ACTIONS,
        ]);

        $config->addRawColumn(static::ACTIONS);

        $config->setSortable([
            SpyCmsPageTableMap::COL_ID_CMS_PAGE,
        ]);

        $config->setSearchable([
            SpyCmsGlossaryKeyMappingTableMap::COL_ID_CMS_GLOSSARY_KEY_MAPPING,
            SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER,
            CmsQueryContainer::KEY => SpyGlossaryKeyTableMap::COL_KEY,
            CmsQueryContainer::TRANS => SpyGlossaryTranslationTableMap::COL_VALUE,
        ]);

        $config->setUrl('table?' . CmsTableConstants::REQUEST_ID_PAGE . '=' . $this->idPage);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array<array<string, mixed>>
     */
    protected function prepareData(TableConfiguration $config): array
    {
        if (!empty($this->searchArray['value'])) {
            $this->placeholders = $this->findPlaceholders($this->searchArray);
        }

        $query = $this->glossaryQuery;
        $queryResults = $this->runQuery($query, $config);

        $mappedPlaceholders = [];
        $results = [];

        foreach ($queryResults as $item) {
            $results[] = [
                SpyCmsGlossaryKeyMappingTableMap::COL_ID_CMS_GLOSSARY_KEY_MAPPING => $item[SpyCmsGlossaryKeyMappingTableMap::COL_ID_CMS_GLOSSARY_KEY_MAPPING],
                SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER => $item[SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER],
                CmsQueryContainer::KEY => $item[CmsQueryContainer::KEY],
                CmsQueryContainer::TRANS => $item[CmsQueryContainer::TRANS],
                static::ACTIONS => implode(' ', $this->buildLinks($item)),
            ];
            $mappedPlaceholders[] = $item[SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER];
        }

        unset($queryResults);

        $results = $this->addExtractedPlaceholders($mappedPlaceholders, $results);

        return $results;
    }

    /**
     * @param array $item
     *
     * @return array<string>
     */
    protected function buildLinks(array $item): array
    {
        $buttons = [];

        $buttons[] = $this->generateEditButton(
            Url::generate(static::URL_CMS_GLOSSARY_EDIT, [
                CmsTableConstants::REQUEST_ID_PAGE => $this->idPage,
                static::REQUEST_ID_MAPPING => $item[SpyCmsGlossaryKeyMappingTableMap::COL_ID_CMS_GLOSSARY_KEY_MAPPING],
            ]),
            'Edit',
        );
        $buttons[] = $this->generateRemoveButton(static::URL_CMS_GLOSSARY_DELETE, 'Delete', [
            CmsTableConstants::REQUEST_ID_PAGE => $this->idPage,
            static::REQUEST_ID_MAPPING => $item[SpyCmsGlossaryKeyMappingTableMap::COL_ID_CMS_GLOSSARY_KEY_MAPPING],
        ]);

        return $buttons;
    }

    /**
     * @param string $placeholder
     *
     * @return string
     */
    protected function buildPlaceholderLinks(string $placeholder): string
    {
        $url = Url::generate('/cms/glossary/add', [
            CmsTableConstants::REQUEST_ID_PAGE => $this->idPage,
            'placeholder' => $placeholder,
        ]);

        return '<a href="' . $url . '" class="btn btn-xs btn-white">Add Glossary</a>';
    }

    /**
     * @param array $mappedPlaceholders
     * @param array $results
     *
     * @return array<array<string, mixed>>
     */
    protected function addExtractedPlaceholders(array $mappedPlaceholders, array $results): array
    {
        foreach ($this->placeholders as $place) {
            if (!in_array($place, $mappedPlaceholders)) {
                $results[] = [
                    SpyCmsGlossaryKeyMappingTableMap::COL_ID_CMS_GLOSSARY_KEY_MAPPING => null,
                    SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER => $place,
                    CmsQueryContainer::KEY => null,
                    CmsQueryContainer::TRANS => null,
                    static::ACTIONS => $this->buildPlaceholderLinks($place),
                ];
            }
        }

        return $results;
    }

    /**
     * @param array<string, mixed> $searchItems
     *
     * @return array<string>
     */
    protected function findPlaceholders(array $searchItems): array
    {
        $foundPlaceholders = [];
        foreach ($this->placeholders as $place) {
            if (stripos($place, $searchItems['value']) !== false) {
                $foundPlaceholders[] = $place;
            }
        }

        return $foundPlaceholders;
    }
}
