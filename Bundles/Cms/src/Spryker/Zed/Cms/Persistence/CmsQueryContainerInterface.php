<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Persistence;

use Orm\Zed\Cms\Persistence\SpyCmsBlockQuery;
use Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery;
use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;
use Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery;

interface CmsQueryContainerInterface
{

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery
     */
    public function queryTemplates();

    /**
     * @param string $path
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery
     */
    public function queryTemplateByPath($path);

    /**
     * @param int $id
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery
     */
    public function queryTemplateById($id);

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPages();

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsBlockQuery
     */
    public function queryBlocks();

    /**
     * @param int $id
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPageById($id);

    /**
     * @param int $idPage
     * @param string $placeholder
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMapping($idPage, $placeholder);

    /**
     * @param int $idMapping
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingById($idMapping);

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappings();

    /**
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingsByPageId($idCmsPage);

    /**
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsBlockQuery
     */
    public function queryBlockByIdPage($idCmsPage);

    /**
     * @param string $blockName
     * @param string $blockType
     * @param string $blockValue
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsBlockQuery
     */
    public function queryBlockByNameAndTypeValue($blockName, $blockType, $blockValue);

    /**
     * @param int $idCategoryNode
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsBlockQuery
     */
    public function queryBlockByIdCategoryNode($idCategoryNode);

}
