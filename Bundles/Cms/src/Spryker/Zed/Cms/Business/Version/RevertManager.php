<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

use Orm\Zed\Cms\Persistence\Map\SpyCmsGlossaryKeyMappingTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageLocalizedAttributesTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class RevertManager implements RevertManagerInterface
{

    /**
     * @var CmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param CmsQueryContainerInterface $queryContainer
     */
    public function __construct(CmsQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int $idCmsVersionOrigin
     * @param int $idCmsVersionTarget
     *
     * @return bool
     */
    public function revertCmsVersion($idCmsVersionOrigin, $idCmsVersionTarget)
    {
        $originVersionEntity = $this->queryContainer->queryCmsVersionById($idCmsVersionOrigin)->findOne();
        $targetVersionEntity = $this->queryContainer->queryCmsVersionById($idCmsVersionTarget)->findOne();

        $originData = json_decode($targetVersionEntity->getData(), true);
        $targetData = json_decode($targetVersionEntity->getData(), true);

//        dump($data);die;


//        $this->revertCmsTemplate($data[SpyCmsPageTableMap::COL_ID_CMS_PAGE], $data[SpyCmsPageTableMap::COL_ID_CMS_PAGE]);
//        $this->revertCmsPageLocalizedAttributes($data['SpyCmsPageLocalizedAttributess']);
        $this->revertCmsGlossaryKeyMapping($originData[SpyCmsPageTableMap::COL_ID_CMS_PAGE], $originData['SpyCmsGlossaryKeyMappings'], $targetData);
//        $this->copyTargetAsNewVersion();
    }

    /**
     * @param int $idCmsPage
     * @param int $fkTemplate
     *
     * @return void
     */
    protected function revertCmsTemplate($idCmsPage, $fkTemplate)
    {
        $cmsPageEntity = $this->queryContainer->queryPageById($idCmsPage)->findOne();
        $cmsPageEntity->setFkTemplate($fkTemplate);

        $cmsPageEntity->save();
    }

    /**
     * @param array $cmsPageAttributes
     *
     * @return void
     */
    protected function revertCmsPageLocalizedAttributes(array $cmsPageAttributes)
    {
        foreach ($cmsPageAttributes as $cmsPageAttribute) {
            $cmsLocalizedAttribute = $this->queryContainer
                ->queryCmsPageLocalizedAttributesByFkPage($cmsPageAttribute[SpyCmsPageLocalizedAttributesTableMap::COL_FK_CMS_PAGE])
                ->filterByFkLocale($cmsPageAttribute[SpyCmsPageLocalizedAttributesTableMap::COL_FK_LOCALE])
                ->findOne();

            $cmsLocalizedAttribute->setName($cmsPageAttribute[SpyCmsPageLocalizedAttributesTableMap::COL_NAME]);
            $cmsLocalizedAttribute->setMetaTitle($cmsPageAttribute[SpyCmsPageLocalizedAttributesTableMap::COL_META_TITLE]);
            $cmsLocalizedAttribute->setMetaKeywords($cmsPageAttribute[SpyCmsPageLocalizedAttributesTableMap::COL_META_KEYWORDS]);
            $cmsLocalizedAttribute->setMetaDescription($cmsPageAttribute[SpyCmsPageLocalizedAttributesTableMap::COL_META_DESCRIPTION]);

            $cmsLocalizedAttribute->save();
        }
    }

    /**
     * @param $idCmsPage
     * @param $cmsGlossaryKeyMappings
     * @param $targetData
     *
     * @return void
     */
    protected function revertCmsGlossaryKeyMapping($idCmsPage, $cmsGlossaryKeyMappings, $targetData)
    {
        $glossaryKeyIds = [];
        foreach ($cmsGlossaryKeyMappings as $cmsGlossaryKeyMapping) {
            $glossaryKeyIds[] = $cmsGlossaryKeyMapping[SpyCmsGlossaryKeyMappingTableMap::COL_FK_GLOSSARY_KEY];
        }

        // 01 - Delete Mappings
        // 02 - Delete Translations
        // 03 - Delete GlossaryKeys
        // 04 - saveCmsGlossary(From CmsGlossarySaver)
    }
}
