<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Mapper;

use Generated\Shared\Transfer\CmsGlossaryAttributesTransfer;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageMetaAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\CmsPlaceholderTranslationTransfer;
use Generated\Shared\Transfer\CmsVersionDataTransfer;
use Generated\Shared\Transfer\CmsVersionTransfer;
use Orm\Zed\Cms\Persistence\Map\SpyCmsGlossaryKeyMappingTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageLocalizedAttributesTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsTemplateTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Spryker\Zed\CmsGui\Communication\Mapper\CmsVersionMapperInterface;
use Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface;
use Spryker\Zed\CmsGui\Dependency\Service\CmsGuiToUtilEncodingInterface;

class CmsVersionMapper implements CmsVersionMapperInterface
{

    /**
     * @var \Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var CmsGuiToUtilEncodingInterface
     */
    protected $utilEncoding;

    /**
     * @param \Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface $cmsQueryContainer
     * @param CmsGuiToUtilEncodingInterface $utilEncoding
     */
    public function __construct(CmsGuiToCmsQueryContainerInterface $cmsQueryContainer, CmsGuiToUtilEncodingInterface $utilEncoding)
    {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->utilEncoding = $utilEncoding;
    }

    /**
     * @param CmsVersionTransfer $cmsVersionTransfer
     *
     * @return CmsVersionDataTransfer
     */
    public function mapToCmsVersionDataTransfer(CmsVersionTransfer $cmsVersionTransfer)
    {
        $cmsVersionData = $this->utilEncoding->decodeJson($cmsVersionTransfer->getData(), true);
        $cmsVersionDataTransfer = (new CmsVersionDataTransfer())->fromArray($cmsVersionData);
        $cmsVersionDataTransfer = $this->mapCmsPageTransferWithUrl($cmsVersionDataTransfer);

        return $cmsVersionDataTransfer;
    }

    /**
     * @param CmsVersionDataTransfer $cmsVersionDataTransfer
     *
     * @return CmsVersionDataTransfer
     */
    protected function mapCmsPageTransferWithUrl(CmsVersionDataTransfer $cmsVersionDataTransfer)
    {
        foreach ($cmsVersionDataTransfer->getCmsPage()->getPageAttributes() as $cmsPageAttributesTransfer) {
            $urlEntity = $this->cmsQueryContainer->queryPageWithUrlByIdCmsPageAndLocaleName(
                $cmsVersionDataTransfer->getCmsPage()->getFkPage(),
                $cmsPageAttributesTransfer->getLocaleName())
                ->findOne();

            $cmsPageAttributesTransfer->setUrl($urlEntity->getUrl());
        }

        return $cmsVersionDataTransfer;
    }

}
