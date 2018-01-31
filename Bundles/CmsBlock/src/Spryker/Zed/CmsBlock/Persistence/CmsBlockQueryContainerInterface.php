<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Persistence;

interface CmsBlockQueryContainerInterface
{
    /**
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockById($idCmsBlock);

    /**
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockByIdWithTemplateWithGlossary($idCmsBlock);

    /**
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockByIdWithTemplateWithGlossaryWithStoreRelation($idCmsBlock);

    /**
     * @api
     *
     * @param string $name
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockByName($name);

    /**
     * @api
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockWithTemplate();

    /**
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMappingQuery
     */
    public function queryCmsBlockGlossaryKeyMappingByIdCmsBlock($idCmsBlock);

    /**
     * @api
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplateQuery
     */
    public function queryTemplates();

    /**
     * @api
     *
     * @param string $path
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplateQuery
     */
    public function queryTemplateByPath($path);

    /**
     * @api
     *
     * @param int $idCmsBlockTemplate
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplateQuery
     */
    public function queryTemplateById($idCmsBlockTemplate);

    /**
     * @api
     *
     * @param array $placeholders
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingByPlaceholdersAndIdCmsBlock(array $placeholders, $idCmsBlock);

    /**
     * @api
     *
     * @param int $idGlossaryKeyMapping
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingById($idGlossaryKeyMapping);

    /**
     * @api
     *
     * @param int $idCmsBlock
     * @param int[] $idStores
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockStoreQuery
     */
    public function queryCmsBlockStoreByFkCmsBlockAndFkStores($idCmsBlock, array $idStores);

    /**
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockWithStoreRelationByFkCmsBlock($idCmsBlock);

    /**
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockStoreQuery
     */
    public function queryCmsBlockStoreWithStoreByFkCmsBlock($idCmsBlock);
}
