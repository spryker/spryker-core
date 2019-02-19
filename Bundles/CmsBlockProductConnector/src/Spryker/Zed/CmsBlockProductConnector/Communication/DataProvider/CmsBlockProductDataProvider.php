<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Communication\DataProvider;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\CmsBlockProductConnector\Communication\Form\CmsBlockProductAbstractType;
use Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\CmsBlockProductConnectorToLocaleInterface;
use Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorRepositoryInterface;

class CmsBlockProductDataProvider
{
    public const PRODUCT_ABSTRACT_VIRTUAL_COLUMN_NAME = 'name';

    /**
     * @var \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface
     */
    protected $cmsBlockProductQueryContainer;

    /**
     * @var \Spryker\Zed\CmsBlockProductConnector\Dependency\QueryContainer\CmsBlockProductConnectorToProductAbstractQueryContainerInterface
     */
    protected $productAbstractQueryContainer;

    /**
     * @var \Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\CmsBlockProductConnectorToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\CmsBlockProductConnectorToLocaleInterface $localeFacade
     * @param \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorRepositoryInterface $repository
     */
    public function __construct(
        CmsBlockProductConnectorToLocaleInterface $localeFacade,
        CmsBlockProductConnectorRepositoryInterface $repository
    ) {
        $this->localeFacade = $localeFacade;
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return array
     */
    public function getOptions(CmsBlockTransfer $cmsBlockTransfer)
    {
        return [
            'data_class' => CmsBlockTransfer::class,
            CmsBlockProductAbstractType::OPTION_PRODUCT_ABSTRACT_ARRAY => $this->getAssignedProductOptions($cmsBlockTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function getData(CmsBlockTransfer $cmsBlockTransfer)
    {
        $idProductAbstracts = [];

        if ($cmsBlockTransfer->getIdCmsBlock()) {
            $idProductAbstracts = $this->repository->getAssignedProductAbstractArray($cmsBlockTransfer->getIdCmsBlock());
        }

        $cmsBlockTransfer->setIdProductAbstracts($idProductAbstracts);

        return $cmsBlockTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return array
     */
    protected function getAssignedProductOptions(CmsBlockTransfer $cmsBlockTransfer): array
    {
        $idLocale = $this->localeFacade
            ->getCurrentLocale()
            ->getIdLocale();

        return $this->repository->getAssignedProductOptions(
            $idLocale,
            $cmsBlockTransfer->getIdCmsBlock()
        );
    }
}
