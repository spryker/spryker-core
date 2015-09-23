<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Business\Manager;

use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use SprykerFeature\Shared\Category\CategoryConfig;
use SprykerFeature\Zed\Category\Business\Generator\UrlPathGeneratorInterface;
use SprykerFeature\Zed\Category\Business\Tree\CategoryTreeReaderInterface;
use SprykerFeature\Zed\Category\Dependency\Facade\CategoryToUrlInterface;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryAttributeTableMap;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryClosureTableTableMap;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryNodeTableMap;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryClosureTableQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryNodeQuery;

class NodeUrlManager implements NodeUrlManagerInterface
{

    /**
     * @var CategoryTreeReaderInterface
     */
    protected $categoryTreeReader;

    /**
     * @var UrlPathGeneratorInterface
     */
    protected $urlPathGenerator;

    /**
     * @var CategoryToUrlInterface
     */
    protected $urlFacade;

    /**
     * @param CategoryTreeReaderInterface $categoryTreeReader
     * @param UrlPathGeneratorInterface $urlPathGenerator
     * @param CategoryToUrlInterface $urlFacade
     */
    public function __construct(
        CategoryTreeReaderInterface $categoryTreeReader,
        UrlPathGeneratorInterface $urlPathGenerator,
        CategoryToUrlInterface $urlFacade
    ) {
        $this->categoryTreeReader = $categoryTreeReader;
        $this->urlPathGenerator = $urlPathGenerator;
        $this->urlFacade = $urlFacade;
    }

    /**
     * @param NodeTransfer $categoryNode
     * @param LocaleTransfer $locale
     */
    public function createUrl(NodeTransfer $categoryNode, LocaleTransfer $locale)
    {
        $path = $this->categoryTreeReader->getPath($categoryNode->getIdCategoryNode(), $locale);
        $categoryUrl = $this->urlPathGenerator->generate($path);
        $idNode = $categoryNode->getIdCategoryNode();

        $url = $this->urlFacade->createUrl($categoryUrl, $locale, CategoryConfig::RESOURCE_TYPE_CATEGORY_NODE, $idNode);
        $this->urlFacade->touchUrlActive($url->getIdUrl());
    }

    /**
     * @param NodeTransfer $categoryNode
     * @param LocaleTransfer $locale
     * @param NodeTransfer|null $parentNode
     */
    public function updateUrl(NodeTransfer $categoryNode, LocaleTransfer $locale, NodeTransfer $parentNode=null)
    {
        $path = $this->categoryTreeReader->getPath($categoryNode->getIdCategoryNode(), $locale);
        $categoryUrl = $this->urlPathGenerator->generate($path);
        $idNode = $categoryNode->getIdCategoryNode();

        $url = $this->urlFacade->getResourceUrlByCategoryNodeIdAndLocale($idNode, $locale);
        
        if (!$url) {
            $urlTransfer = new UrlTransfer();
            $urlTransfer->setFkPage(null);
            $urlTransfer->setResourceId($idNode);
            $urlTransfer->setResourceType(CategoryConfig::RESOURCE_TYPE_CATEGORY_NODE);
            $urlTransfer->setUrl($categoryUrl);
            $urlTransfer->setFkLocale($locale->getIdLocale());

            $this->urlFacade->saveUrlAndTouch($urlTransfer);
        }
        else {
            $url->setUrl($categoryUrl);
            $this->urlFacade->saveUrlAndTouch($url);
        }

        //TODO implement deep fix //https://spryker.atlassian.net/browse/CD-459
        //$nodes = $this->categoryTreeReader->getChildren($idNode, $locale);


        $query = new SpyCategoryClosureTableQuery();
            $query->filterByFkCategoryNode($categoryNode->getIdCategoryNode())
            //->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, 'id_category_node')
            //->withColumn(SpyCategoryClosureTableTableMap::COL_ID_CATEGORY_CLOSURE_TABLE, 'id_category_closure_table')
            //->withColumn(SpyCategoryClosureTableTableMap::COL_DEPTH, 'depth')
            ->where(SpyCategoryClosureTableTableMap::COL_DEPTH . '> ?', 0)
        ;

        $nodes = $query->find();
        foreach ($nodes as $child) {
            //$childTransfer = (new nodeTransfer())->fromArray($child->toArray());

            $query = new SpyCategoryClosureTableQuery();
            $query->filterByFkCategoryNodeDescendant($child->getFkCategoryNodeDescendant())
                ->innerJoinNode()
                ->useNodeQuery()
                  ->innerJoinCategory()
                  ->useCategoryQuery()
                    ->innerJoinAttribute()
                  ->endUse()
                ->endUse()
                //->where(SpyCategoryClosureTableTableMap::COL_DEPTH . ' > ?', 0)
                ->where(SpyCategoryNodeTableMap::COL_IS_ROOT . ' = false')
                ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, 'name')
                ->withColumn(SpyCategoryAttributeTableMap::COL_URL_KEY, 'url_key')
                ->orderBy(SpyCategoryClosureTableTableMap::COL_DEPTH, 'DESC')
            ;

            $parents = $query->find();
            $nodes = [];
            foreach ($parents as $parent) {
                $nodes[] = $parent->toArray();
            }

            //$nodes[] = $categoryNode->toArray();
            dump($nodes);

            $categoryUrl = $this->urlPathGenerator->generate($nodes);
            dump($categoryUrl);

            //$childPath = array_merge($path, $childPath);
/*            $categoryUrl = $this->urlPathGenerator->generate($childPath);
            $url->setUrl($categoryUrl);
            $this->urlFacade->saveUrlAndTouch($url);*/
            //$childTransfer = (new NodeTransfer())->fromArray($child->toArray());
            //$this->updateUrl($childTransfer, $locale);

        }

        die();
    }

    /**
     * @param NodeTransfer $categoryNode
     * @param LocaleTransfer $locale
     */
    public function removeUrl(NodeTransfer $categoryNode, LocaleTransfer $locale)
    {
        $path = $this->categoryTreeReader->getPath($categoryNode->getIdCategoryNode(), $locale);
        $categoryUrl = $this->urlPathGenerator->generate($path);
        $idNode = $categoryNode->getIdCategoryNode();
        
        if ($this->urlFacade->hasUrl($categoryUrl)) {
            $url = $this->urlFacade->getUrlByPath($categoryUrl);
            $this->urlFacade->touchUrlDeleted($url->getIdUrl());
        }
    }
}
