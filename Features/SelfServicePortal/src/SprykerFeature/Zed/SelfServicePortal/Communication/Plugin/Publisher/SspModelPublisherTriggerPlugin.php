<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SspModelCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 */
class SspModelPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\SelfServicePortal\Persistence\Map\SpySspModelTableMap::COL_ID_SSP_MODEL
     *
     * @var string
     */
    protected const COL_ID_SSP_MODEL = 'spy_ssp_model.id_ssp_model';

    /**
     * {@inheritDoc}
     * - Retrieves SSP models by provided limit and offset.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array<\Generated\Shared\Transfer\SspModelTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        $paginationTransfer = (new PaginationTransfer())
            ->setOffset($offset)
            ->setLimit($limit);

        $sspModelCriteriaTransfer = (new SspModelCriteriaTransfer())->setPagination($paginationTransfer);

        return $this->getFacade()
            ->getSspModelCollection($sspModelCriteriaTransfer)
            ->getSspModels()
            ->getArrayCopy();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return SelfServicePortalConfig::SSP_MODEL_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getEventName(): string
    {
        return SelfServicePortalConfig::SSP_MODEL_PUBLISH;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getIdColumnName(): ?string
    {
        return static::COL_ID_SSP_MODEL;
    }
}
