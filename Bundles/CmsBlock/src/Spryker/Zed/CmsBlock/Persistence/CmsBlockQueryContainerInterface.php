<?php


namespace Spryker\Zed\CmsBlock\Persistence;


use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMappingQuery;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplateQuery;

interface CmsBlockQueryContainerInterface
{
    /**
     * @param $idCmsBlock
     *
     * @return SpyCmsBlockQuery
     */
    public function queryCmsBlockById($idCmsBlock);

    /**
     * @param int $idCmsBlock
     *
     * @return SpyCmsBlockQuery
     */
    public function queryCmsBlockByIdWithTemplateWithGlossary($idCmsBlock);

    /**
     * @param string $name
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockByName($name);

    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockWithTemplate();

    /**
     * @param int $idCmsBlock
     *
     * @return SpyCmsBlockGlossaryKeyMappingQuery
     */
    public function queryCmsBlockGlossaryKeyMappingByIdCmsBlock($idCmsBlock);


    /**
     * @return SpyCmsBlockTemplateQuery
     */
    public function queryTemplates();

    /**
     * @param string $path
     *
     * @return SpyCmsBlockTemplateQuery
     */
    public function queryTemplateByPath($path);

    /**
     * @param array $placeholders
     * @param int $idCmsBlock
     * @return SpyCmsBlockGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingByPlaceholdersAndIdCmsBlock(array $placeholders, $idCmsBlock);

    /**
     * @param int $idGlossaryKeyMapping
     *
     * @return SpyCmsBlockGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingById($idGlossaryKeyMapping);

}