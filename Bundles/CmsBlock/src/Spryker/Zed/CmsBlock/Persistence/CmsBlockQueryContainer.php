<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Persistence;

use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTemplateTableMap;
use Spryker\Zed\CmsBlock\CmsBlockDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\CmsBlock\Persistence\CmsBlockPersistenceFactory getFactory()
 */
class CmsBlockQueryContainer extends AbstractQueryContainer implements CmsBlockQueryContainerInterface
{
    const COLUMN_TEMPLATE_NAME = 'template_name';

    /**
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockById($idCmsBlock)
    {
        return $this->queryCmsBlock()
            ->filterByIdCmsBlock($idCmsBlock);
    }

    /**
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockByIdWithTemplateWithGlossary($idCmsBlock)
    {
        return $this->queryCmsBlock()
            ->filterByIdCmsBlock($idCmsBlock)
            ->leftJoinWithCmsBlockTemplate()
            ->leftJoinWithSpyCmsBlockGlossaryKeyMapping();
    }

    /**
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockByIdWithTemplateWithGlossaryWithStoreRelation($idCmsBlock)
    {
        return $this->queryCmsBlockByIdWithTemplateWithGlossary($idCmsBlock)
            ->leftJoinWithSpyCmsBlockStore()
            ->useSpyCmsBlockStoreQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinWithSpyStore()
            ->endUse();
    }

    /**
     * @api
     *
     * @param string $name
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockByName($name)
    {
        return $this->queryCmsBlock()
            ->filterByName($name);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockWithTemplate()
    {
        return $this->queryCmsBlock()
            ->leftJoinCmsBlockTemplate()
                ->withColumn(SpyCmsBlockTemplateTableMap::COL_TEMPLATE_NAME, static::COLUMN_TEMPLATE_NAME);
    }

    /**
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMappingQuery
     */
    public function queryCmsBlockGlossaryKeyMappingByIdCmsBlock($idCmsBlock)
    {
        return $this->queryCmsBlockGlossaryKeyMapping()
            ->filterByFkCmsBlock($idCmsBlock);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplateQuery
     */
    public function queryTemplates()
    {
        return $this->getFactory()
            ->createCmsBlockTemplateQuery();
    }

    /**
     * @api
     *
     * @param string $path
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplateQuery
     */
    public function queryTemplateByPath($path)
    {
        return $this->queryTemplates()
            ->filterByTemplatePath($path);
    }

    /**
     * @api
     *
     * @param int $idCmsBlockTemplate
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplateQuery
     */
    public function queryTemplateById($idCmsBlockTemplate)
    {
        return $this->queryTemplates()
            ->filterByIdCmsBlockTemplate($idCmsBlockTemplate);
    }

    /**
     * @api
     *
     * @param array $placeholders
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingByPlaceholdersAndIdCmsBlock(array $placeholders, $idCmsBlock)
    {
        return $this->queryCmsBlockGlossaryKeyMapping()
            ->filterByFkCmsBlock($idCmsBlock)
            ->filterByPlaceholder_In($placeholders);
    }

    /**
     * @api
     *
     * @param int $idGlossaryKeyMapping
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingById($idGlossaryKeyMapping)
    {
        return $this->queryCmsBlockGlossaryKeyMapping()
            ->filterByIdCmsBlockGlossaryKeyMapping($idGlossaryKeyMapping);
    }

    /**
     * @api
     *
     * @param string $key
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryKey($key)
    {
        return $this->getGlossaryQueryContainer()
            ->queryKey($key);
    }

    /**
     * @api
     *
     * @param int $idCmsBlock
     * @param int[] $idStores
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockStoreQuery
     */
    public function queryCmsBlockStoreByFkCmsBlockAndFkStores($idCmsBlock, array $idStores)
    {
        return $this->getFactory()
            ->createCmsBlockStoreQuery()
            ->filterByFkCmsBlock($idCmsBlock)
            ->filterByFkStore_In($idStores);
    }

    /**
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockWithStoreRelationByFkCmsBlock($idCmsBlock)
    {
        return $this
            ->queryCmsBlock()
            ->filterByIdCmsBlock($idCmsBlock)
            ->leftJoinWithSpyCmsBlockStore()
            ->useSpyCmsBlockStoreQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinWithSpyStore()
            ->endUse();
    }

    /**
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockStoreQuery
     */
    public function queryCmsBlockStoreWithStoreByFkCmsBlock($idCmsBlock)
    {
        return $this->getFactory()
            ->createCmsBlockStoreQuery()
            ->filterByFkCmsBlock($idCmsBlock)
            ->joinWithSpyStore();
    }

    /**
     * @return \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface
     */
    protected function getGlossaryQueryContainer()
    {
        return $this->getProvidedDependency(CmsBlockDependencyProvider::QUERY_CONTAINER_GLOSSARY);
    }

    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    protected function queryCmsBlock()
    {
        return $this->getFactory()
            ->createCmsBlockQuery();
    }

    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMappingQuery
     */
    protected function queryCmsBlockGlossaryKeyMapping()
    {
        return $this->getFactory()
            ->createCmsBlockGlossaryKeyMappingQuery();
    }
}
