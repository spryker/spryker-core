<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Mapper;

use Generated\Shared\Transfer\CmsVersionDataTransfer;
use Generated\Shared\Transfer\CmsVersionTransfer;
use Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface;
use Spryker\Zed\CmsGui\Dependency\Service\CmsGuiToUtilEncodingInterface;

class CmsVersionMapper implements CmsVersionMapperInterface
{
    /**
     * @var \Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Spryker\Zed\CmsGui\Dependency\Service\CmsGuiToUtilEncodingInterface
     */
    protected $utilEncoding;

    /**
     * @param \Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\CmsGui\Dependency\Service\CmsGuiToUtilEncodingInterface $utilEncoding
     */
    public function __construct(CmsGuiToCmsQueryContainerInterface $cmsQueryContainer, CmsGuiToUtilEncodingInterface $utilEncoding)
    {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->utilEncoding = $utilEncoding;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsVersionTransfer $cmsVersionTransfer
     *
     * @return \Generated\Shared\Transfer\CmsVersionDataTransfer
     */
    public function mapToCmsVersionDataTransfer(CmsVersionTransfer $cmsVersionTransfer): CmsVersionDataTransfer
    {
        $cmsVersionData = $this->utilEncoding->decodeJson($cmsVersionTransfer->getData(), true);
        $cmsVersionDataTransfer = (new CmsVersionDataTransfer())->fromArray($cmsVersionData);
        $cmsVersionDataTransfer = $this->mapCmsPageTransferWithUrl($cmsVersionDataTransfer);

        return $cmsVersionDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsVersionDataTransfer $cmsVersionDataTransfer
     *
     * @return \Generated\Shared\Transfer\CmsVersionDataTransfer
     */
    public function mapCmsPageTransferWithUrl(CmsVersionDataTransfer $cmsVersionDataTransfer): CmsVersionDataTransfer
    {
        foreach ($cmsVersionDataTransfer->getCmsPage()->getPageAttributes() as $cmsPageAttributesTransfer) {
            $urlEntity = $this->cmsQueryContainer->queryPageWithUrlByIdCmsPageAndLocaleName(
                $cmsVersionDataTransfer->getCmsPage()->getFkPage(),
                $cmsPageAttributesTransfer->getLocaleName()
            )
                ->findOne();

            $cmsPageAttributesTransfer->setUrl($urlEntity->getUrl());
        }

        return $cmsVersionDataTransfer;
    }
}
