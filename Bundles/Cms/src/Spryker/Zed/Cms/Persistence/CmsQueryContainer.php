<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Shared\Cms\CmsConstants;
use Orm\Zed\Category\Persistence\Base\SpyCategoryNodeQuery;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Spryker\Zed\Category\Persistence\CategoryQueryContainer;
use Spryker\Zed\Cms\CmsDependencyProvider;
use Spryker\Zed\Cms\Communication\Form\CmsBlockForm;
use Spryker\Zed\Cms\Communication\Form\CmsPageForm;
use Orm\Zed\Cms\Persistence\Map\SpyCmsBlockTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsTemplateTableMap;
use Orm\Zed\Cms\Persistence\SpyCmsBlockQuery;
use Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery;
use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;
use Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery;
use Orm\Zed\Glossary\Persistence\Base\SpyGlossaryTranslationQuery;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Orm\Zed\Url\Persistence\SpyUrlQuery;

class CmsQueryContainer extends AbstractQueryContainer implements CmsQueryContainerInterface
{

    const TEMPLATE_NAME = 'template_name';
    const TEMPLATE_PATH = 'template_path';
    const CATEGORY_NODE_ID = 'categoryNodeId';
    const CATEGORY_NAME = 'categoryName';
    const ID_URL = 'id_url';
    const URL = 'url';
    const TO_URL = 'toUrl';
    const TRANS = 'trans';
    const KEY = 'keyname';
    const LABEL = 'label';
    const VALUE = 'value';

    /**
     * @return SpyCmsTemplateQuery
     */
    public function queryTemplates()
    {
        $query = SpyCmsTemplateQuery::create();

        return $query;
    }

    /**
     * @param string $path
     *
     * @return SpyCmsTemplateQuery
     */
    public function queryTemplateByPath($path)
    {
        $query = $this->queryTemplates();
        $query->filterByTemplatePath($path);

        return $query;
    }

    /**
     * @param int $id
     *
     * @return SpyCmsTemplateQuery
     */
    public function queryTemplateById($id)
    {
        $query = $this->queryTemplates();
        $query->filterByIdCmsTemplate($id);

        return $query;
    }

    /**
     * @return SpyCmsPageQuery
     */
    public function queryPages()
    {
        $query = SpyCmsPageQuery::create();

        return $query;
    }

    /**
     * @return SpyCmsBlockQuery
     */
    public function queryBlocks()
    {
        $query = SpyCmsBlockQuery::create();

        return $query;
    }

    /**
     * @return self|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryPagesWithTemplates()
    {
        return $this->queryPages()
            ->leftJoinCmsTemplate()
            ->withColumn(self::TEMPLATE_NAME)
            ->withColumn(self::TEMPLATE_PATH);
    }

    /**
     * @return SpyCmsPageQuery
     */
    public function queryPageWithTemplatesAndUrls()
    {
        return $this->queryPages()
            ->leftJoinCmsTemplate()
            ->innerJoinSpyUrl()
            ->withColumn(self::TEMPLATE_NAME)
            ->withColumn(self::URL);
    }

    /**
     * @param int $idLocale
     *
     * @return SpyCmsBlockQuery
     */
    public function queryPageWithTemplatesAndBlocks($idLocale)
    {
        return $this->queryBlocks()
            ->leftJoinSpyCmsPage()
            ->useSpyCmsPageQuery()
                ->joinCmsTemplate()
                    ->withColumn(SpyCmsTemplateTableMap::COL_TEMPLATE_NAME, self::TEMPLATE_NAME)
                ->endUse()
            ->addJoin(
                SpyCmsBlockTableMap::COL_VALUE,
                SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE,
                Criteria::LEFT_JOIN
            )
            ->addJoin(
                [SpyCategoryNodeTableMap::COL_FK_CATEGORY, SpyCategoryAttributeTableMap::COL_FK_LOCALE],
                [SpyCategoryAttributeTableMap::COL_FK_CATEGORY, $idLocale],
                Criteria::LEFT_JOIN
            )
            ->addJoin(
                [SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, SpyUrlTableMap::COL_FK_LOCALE],
                [SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE, $idLocale],
                Criteria::LEFT_JOIN
            )
            ->withColumn(SpyUrlTableMap::COL_URL, self::URL)
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, self::CATEGORY_NAME)
            ->withColumn(SpyCmsBlockTableMap::COL_NAME);
    }

    /**
     * @param int $idCmsBlock
     *
     * @return SpyCmsBlockQuery
     */
    public function queryPageWithTemplatesAndBlocksById($idCmsBlock)
    {
        return $this->queryBlocks()
            ->filterByIdCmsBlock($idCmsBlock)
            ->leftJoinSpyCmsPage()
            ->useSpyCmsPageQuery()
                ->joinCmsTemplate()
                    ->withColumn(SpyCmsTemplateTableMap::COL_TEMPLATE_NAME, self::TEMPLATE_NAME)
                ->endUse()
            ->addJoin(
                SpyCmsBlockTableMap::COL_VALUE,
                SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE,
                Criteria::LEFT_JOIN
            )
            ->addJoin(
                SpyCategoryNodeTableMap::COL_FK_CATEGORY,
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY,
                Criteria::LEFT_JOIN
            )
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, self::CATEGORY_NAME)
            ->withColumn(SpyCmsBlockTableMap::COL_NAME)
            ->withColumn(SpyCmsPageTableMap::COL_FK_TEMPLATE, CmsBlockForm::FIELD_FK_TEMPLATE)
            ->withColumn(SpyCmsPageTableMap::COL_IS_ACTIVE, 'isActive');
    }

    /**
     * @param int $id
     *
     * @return SpyCmsPageQuery
     */
    public function queryPageById($id)
    {
        $query = $this->queryPages();
        $query->filterByIdCmsPage($id);

        return $query;
    }

    /**
     * @param int $idPage
     * @param string $placeholder
     *
     * @return SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMapping($idPage, $placeholder)
    {
        $query = $this->queryGlossaryKeyMappings();
        $query->filterByFkPage($idPage)
            ->filterByPlaceholder($placeholder);

        return $query;
    }

    /**
     * @param int $idMapping
     *
     * @return SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingById($idMapping)
    {
        $query = $this->queryGlossaryKeyMappings();
        $query->filterByIdCmsGlossaryKeyMapping($idMapping);

        return $query;
    }

    /**
     * @param int $idMapping
     *
     * @return SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingWithKeyById($idMapping)
    {
        $query = $this->queryGlossaryKeyMappings();
        $query->filterByIdCmsGlossaryKeyMapping($idMapping)
            ->leftJoinGlossaryKey()
            ->withColumn(SpyGlossaryKeyTableMap::COL_KEY, self::KEY);

        return $query;
    }

    /**
     * @return SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappings()
    {
        $query = SpyCmsGlossaryKeyMappingQuery::create();

        return $query;
    }

    /**
     * @param int $idCmsPage
     *
     * @return SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingsByPageId($idCmsPage)
    {
        $query = $this->queryGlossaryKeyMappings();
        $query->filterByFkPage($idCmsPage);

        return $query;
    }

    /**
     * @param int $idCmsPage
     * @param int $fkLocale
     *
     * @return SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingsWithKeyByPageId($idCmsPage, $fkLocale)
    {
        $query = $this->queryGlossaryKeyMappings()
            ->withColumn(SpyGlossaryKeyTableMap::COL_KEY, self::KEY)
            ->withColumn(SpyGlossaryTranslationTableMap::COL_VALUE, self::TRANS)
            ->filterByFkPage($idCmsPage)
            ->useGlossaryKeyQuery()
                ->useSpyGlossaryTranslationQuery()
                    ->filterByFkLocale($fkLocale)
                ->endUse()
            ->endUse();

        return $query;
    }

    /**
     * @param int $idUrl
     *
     * @return SpyUrlQuery
     */
    public function queryUrlByIdWithRedirect($idUrl)
    {
        return $this->getProvidedDependency(CmsDependencyProvider::QUERY_CONTAINER_URL)
            ->queryUrlByIdWithRedirect($idUrl);
    }

    /**
     * @param int $idUrlRedirect
     *
     * @return SpyUrlQuery
     */
    public function queryRedirectById($idUrlRedirect)
    {
        return $this->getProvidedDependency(CmsDependencyProvider::QUERY_CONTAINER_URL)
            ->queryRedirectById($idUrlRedirect);
    }

    /**
     * @return SpyUrlQuery
     */
    public function queryUrlsWithRedirect()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::QUERY_CONTAINER_URL)
            ->queryUrlsWithRedirect();
    }

    /**
     * @param string $key
     *
     * @return SpyGlossaryKeyQuery
     */
    public function queryKey($key)
    {
        return $this->getProvidedDependency(CmsDependencyProvider::QUERY_CONTAINER_GLOSSARY)
            ->queryKey($key);
    }

    /**
     * @param int $idCmsPage
     *
     * @return SpyCmsPageQuery
     */
    public function queryPageWithTemplatesAndUrlByIdPage($idCmsPage)
    {
        return $this->queryPages()
            ->leftJoinCmsTemplate()
            ->leftJoinSpyUrl()
            ->withColumn(SpyCmsTemplateTableMap::COL_TEMPLATE_NAME, self::TEMPLATE_NAME)
            ->withColumn(SpyUrlTableMap::COL_URL, self::URL)
            ->withColumn(SpyUrlTableMap::COL_ID_URL, 'idUrl')
            ->withColumn(SpyCmsTemplateTableMap::COL_TEMPLATE_PATH, self::TEMPLATE_PATH)
            ->withColumn(CmsPageForm::FIELD_IS_ACTIVE)
            ->filterByIdCmsPage($idCmsPage);
    }

    /**
     * @param int $idUrl
     *
     * @return SpyUrlQuery
     */
    public function queryUrlById($idUrl)
    {
        return $this->getProvidedDependency(CmsDependencyProvider::QUERY_CONTAINER_URL)
            ->queryUrlById($idUrl);
    }

    /**
     * @param string $value
     *
     * @return SpyGlossaryTranslationQuery
     */
    public function queryTranslationWithKeyByValue($value)
    {
        return $this->getProvidedDependency(CmsDependencyProvider::QUERY_CONTAINER_GLOSSARY)
            ->queryTranslationByValue($value)
            ->innerJoinGlossaryKey()
            ->filterByIsActive(true)
            ->withColumn(SpyGlossaryKeyTableMap::COL_KEY, self::LABEL)
            ->withColumn(SpyGlossaryTranslationTableMap::COL_VALUE, self::VALUE);
    }

    /**
     * @param string $key
     *
     * @return SpyUrlQuery
     */
    public function queryKeyWithTranslationByKey($key)
    {
        return $this->getProvidedDependency(CmsDependencyProvider::QUERY_CONTAINER_GLOSSARY)
            ->queryByKey($key)
            ->rightJoinSpyGlossaryTranslation()
            ->filterByIsActive(true)
            ->withColumn(SpyGlossaryKeyTableMap::COL_KEY, self::LABEL)
            ->withColumn(SpyGlossaryTranslationTableMap::COL_VALUE, self::VALUE);
    }

    /**
     * @param int $idCmsPage
     *
     * @return SpyCmsBlockQuery
     */
    public function queryBlockByIdPage($idCmsPage)
    {
        return $this->queryBlocks()
            ->filterByFkPage($idCmsPage);
    }

    /**
     * @param string $blockName
     * @param string $blockType
     * @param string $blockValue
     *
     * @return SpyCmsBlockQuery
     */
    public function queryBlockByNameAndTypeValue($blockName, $blockType, $blockValue)
    {
        return $this->queryBlocks()
            ->filterByName($blockName)
            ->filterByType($blockType)
            ->filterByValue($blockValue);
    }

    /**
     * @param string $categoryName
     * @param int $idLocale
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryNodeByCategoryName($categoryName, $idLocale)
    {
        return $this->getCategoryQueryContainer()
            ->queryCategoryNode($idLocale)
            ->useCategoryQuery()
                ->useAttributeQuery()
                    ->where('lower(' . SpyCategoryAttributeTableMap::COL_NAME . ') like ?', '%' . mb_strtolower($categoryName) . '%')
                    ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, self::CATEGORY_NAME)
                ->endUse()
            ->endUse()
            ->useSpyUrlQuery()
                ->filterByFkLocale($idLocale)
            ->endUse()
            ->withColumn(SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE, self::CATEGORY_NODE_ID)
            ->withColumn(SpyUrlTableMap::COL_URL, self::URL);
    }

    /**
     * @return CategoryQueryContainer
     */
    protected function getCategoryQueryContainer()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::QUERY_CONTAINER_CATEGORY);
    }

    /**
     * @param int $idCategoryNode
     *
     * @return SpyCmsBlockQuery
     */
    public function queryBlockByIdCategoryNode($idCategoryNode)
    {
        return $this->queryBlocks()
            ->filterByType(CmsConstants::RESOURCE_TYPE_CATEGORY_NODE)
            ->filterByValue($idCategoryNode);
    }

}
