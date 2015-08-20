<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Persistence;

use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsBlockQuery;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsGlossaryKeyMappingQuery;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsPageQuery;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsTemplateQuery;

interface CmsQueryContainerInterface
{

    /**
     * @return SpyCmsTemplateQuery
     */
    public function queryTemplates();

    /**
     * @param string $path
     *
     * @return SpyCmsTemplateQuery
     */
    public function queryTemplateByPath($path);

    /**
     * @param int $id
     *
     * @return SpyCmsTemplateQuery
     */
    public function queryTemplateById($id);

    /**
     * @return SpyCmsPageQuery
     */
    public function queryPages();

    /**
     * @return SpyCmsBlockQuery
     */
    public function queryBlocks();

    /**
     * @param int $id
     *
     * @return SpyCmsPageQuery
     */
    public function queryPageById($id);

    /**
     * @param int $idPage
     * @param string $placeholder
     *
     * @return SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMapping($idPage, $placeholder);

    /**
     * @param int $idMapping
     *
     * @return SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingById($idMapping);

    /**
     * @return SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappings();

    /**
     * @param int $idCmsPage
     *
     * @return SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingsByPageId($idCmsPage);

    /**
     * @param int $idCmsPage
     *
     * @return SpyCmsBlockQuery
     */
    public function queryBlockByIdPage($idCmsPage);

    /**
     * @param string $blockName
     *
     * @return SpyCmsBlockQuery
     */
    public function queryBlockByName($blockName);

}
