<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\CmsGui\Dependency\QueryContainer;

interface CmsGuiToCmsQueryContainerInterface
{

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery
     */
    public function queryTemplates();

    /**
     * @param int $idPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsBlockQuery
     */
    public function queryBlockByIdPage($idPage);

    /**
     * @param int $idPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPageWithTemplatesAndUrlByIdPage($idPage);

    /**
     * @param int $idPage
     * @param int $idLocale
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingsWithKeyByPageId($idPage, $idLocale);
}
