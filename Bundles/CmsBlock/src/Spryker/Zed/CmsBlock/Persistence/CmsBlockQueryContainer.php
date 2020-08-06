<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Persistence;

use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTemplateTableMap;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMappingQuery;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockStoreQuery;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplateQuery;
use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;
use Spryker\Zed\CmsBlock\CmsBlockDependencyProvider;
use Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\CmsBlock\Persistence\CmsBlockPersistenceFactory getFactory()
 */
class CmsBlockQueryContainer extends AbstractQueryContainer implements CmsBlockQueryContainerInterface
{
    public const COLUMN_TEMPLATE_NAME = 'template_name';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockById(int $idCmsBlock): SpyCmsBlockQuery
    {
        return $this->queryCmsBlock()
            ->filterByIdCmsBlock($idCmsBlock);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockByIdWithTemplateWithGlossary(int $idCmsBlock): SpyCmsBlockQuery
    {
        return $this->queryCmsBlock()
            ->filterByIdCmsBlock($idCmsBlock)
            ->leftJoinWithCmsBlockTemplate()
            ->leftJoinWithSpyCmsBlockGlossaryKeyMapping();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockByIdWithTemplateWithGlossaryWithStoreRelation(int $idCmsBlock): SpyCmsBlockQuery
    {
        return $this->queryCmsBlockByIdWithTemplateWithGlossary($idCmsBlock)
            ->leftJoinWithSpyCmsBlockStore()
            ->useSpyCmsBlockStoreQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinWithSpyStore()
            ->endUse();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $name
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockByName(string $name): SpyCmsBlockQuery
    {
        return $this->queryCmsBlock()
            ->filterByName($name);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockWithTemplate(): SpyCmsBlockQuery
    {
        /** @var \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery $query */
        $query = $this->queryCmsBlock()
            ->leftJoinCmsBlockTemplate()
            ->withColumn(SpyCmsBlockTemplateTableMap::COL_TEMPLATE_NAME, static::COLUMN_TEMPLATE_NAME);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMappingQuery
     */
    public function queryCmsBlockGlossaryKeyMappingByIdCmsBlock(int $idCmsBlock): SpyCmsBlockGlossaryKeyMappingQuery
    {
        return $this->queryCmsBlockGlossaryKeyMapping()
            ->filterByFkCmsBlock($idCmsBlock);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplateQuery
     */
    public function queryTemplates(): SpyCmsBlockTemplateQuery
    {
        return $this->getFactory()
            ->createCmsBlockTemplateQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $path
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplateQuery
     */
    public function queryTemplateByPath(string $path): SpyCmsBlockTemplateQuery
    {
        return $this->queryTemplates()
            ->filterByTemplatePath($path);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCmsBlockTemplate
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplateQuery
     */
    public function queryTemplateById(int $idCmsBlockTemplate): SpyCmsBlockTemplateQuery
    {
        return $this->queryTemplates()
            ->filterByIdCmsBlockTemplate($idCmsBlockTemplate);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $placeholders
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingByPlaceholdersAndIdCmsBlock(
        array $placeholders,
        int $idCmsBlock
    ): SpyCmsBlockGlossaryKeyMappingQuery {
        return $this->queryCmsBlockGlossaryKeyMapping()
            ->filterByFkCmsBlock($idCmsBlock)
            ->filterByPlaceholder_In($placeholders);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idGlossaryKeyMapping
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingById(int $idGlossaryKeyMapping): SpyCmsBlockGlossaryKeyMappingQuery
    {
        return $this->queryCmsBlockGlossaryKeyMapping()
            ->filterByIdCmsBlockGlossaryKeyMapping($idGlossaryKeyMapping);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $key
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryKey(string $key): SpyGlossaryKeyQuery
    {
        return $this->getGlossaryQueryContainer()
            ->queryKey($key);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCmsBlock
     * @param int[] $idStores
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockStoreQuery
     */
    public function queryCmsBlockStoreByFkCmsBlockAndFkStores(int $idCmsBlock, array $idStores): SpyCmsBlockStoreQuery
    {
        return $this->getFactory()
            ->createCmsBlockStoreQuery()
            ->filterByFkCmsBlock($idCmsBlock)
            ->filterByFkStore_In($idStores);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockWithStoreRelationByFkCmsBlock(int $idCmsBlock): SpyCmsBlockQuery
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockStoreQuery
     */
    public function queryCmsBlockStoreWithStoreByFkCmsBlock(int $idCmsBlock): SpyCmsBlockStoreQuery
    {
        return $this->getFactory()
            ->createCmsBlockStoreQuery()
            ->filterByFkCmsBlock($idCmsBlock)
            ->joinWithSpyStore();
    }

    /**
     * @return \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface
     */
    protected function getGlossaryQueryContainer(): GlossaryQueryContainerInterface
    {
        return $this->getProvidedDependency(CmsBlockDependencyProvider::QUERY_CONTAINER_GLOSSARY);
    }

    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    protected function queryCmsBlock(): SpyCmsBlockQuery
    {
        return $this->getFactory()
            ->createCmsBlockQuery();
    }

    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMappingQuery
     */
    protected function queryCmsBlockGlossaryKeyMapping(): SpyCmsBlockGlossaryKeyMappingQuery
    {
        return $this->getFactory()
            ->createCmsBlockGlossaryKeyMappingQuery();
    }
}
