<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\CmsGui\Dependency\QueryContainer;

use Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery;
use Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery;
use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;

interface CmsGuiToCmsQueryContainerInterface
{
    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery
     */
    public function queryTemplates();

    /**
     * @param string $value
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationWithKeyByValue(string $value): SpyGlossaryTranslationQuery;

    /**
     * @param string $key
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery|\Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryKeyWithTranslationByKey(string $key);

    /**
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryPagesWithTemplatesForSelectedLocale(int $idLocale): ModelCriteria;

    /**
     * @param int $idCmsPage
     * @param string $localName
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPageWithUrlByIdCmsPageAndLocaleName(int $idCmsPage, string $localName): SpyCmsPageQuery;

    /**
     * @param int $idPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery
     */
    public function queryCmsPageLocalizedAttributesByFkPage(int $idPage): SpyCmsPageLocalizedAttributesQuery;

    /**
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryPagesWithTemplatesForSelectedLocaleAndVersion(int $idLocale): ModelCriteria;

    /**
     * @param array $placeholders
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function queryGlossaryKeyMappingByPlaceholdersAndIdPage(array $placeholders, int $idCmsPage): SpyCmsGlossaryKeyMappingQuery;

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery
     */
    public function queryCmsPageLocalizedAttributes();

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryLocalizedPagesWithTemplates(): SpyCmsPageQuery;
}
