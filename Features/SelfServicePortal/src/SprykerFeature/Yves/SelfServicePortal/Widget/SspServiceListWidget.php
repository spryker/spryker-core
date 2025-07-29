<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Generated\Shared\Transfer\SspServiceCollectionTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

class SspServiceListWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_TOTAL_ITEMS = 'totalItems';

    /**
     * @var string
     */
    protected const PARAMETER_SERVICES = 'services';

    /**
     * @var string
     */
    protected const PARAMETER_MORE_LINK = 'moreLink';

    /**
     * @param \Generated\Shared\Transfer\SspServiceCollectionTransfer|null $sspServiceCollectionTransfer
     * @param string|null $moreLink
     */
    public function __construct(?SspServiceCollectionTransfer $sspServiceCollectionTransfer, ?string $moreLink = null)
    {
        $this->addTotalItemsParameter($sspServiceCollectionTransfer);
        $this->addServicesParameter($sspServiceCollectionTransfer);
        $this->addMoreLinkParameter($moreLink);
    }

    /**
     * @param \Generated\Shared\Transfer\SspServiceCollectionTransfer|null $sspServiceCollectionTransfer
     *
     * @return void
     */
    protected function addTotalItemsParameter(?SspServiceCollectionTransfer $sspServiceCollectionTransfer): void
    {
        $this->addParameter(static::PARAMETER_TOTAL_ITEMS, $sspServiceCollectionTransfer?->getPagination()?->getNbResults());
    }

    /**
     * @param \Generated\Shared\Transfer\SspServiceCollectionTransfer|null $sspServiceCollectionTransfer
     *
     * @return void
     */
    protected function addServicesParameter(?SspServiceCollectionTransfer $sspServiceCollectionTransfer): void
    {
        $this->addParameter(static::PARAMETER_SERVICES, $sspServiceCollectionTransfer?->getServices());
    }

    /**
     * @param string|null $moreLink
     *
     * @return void
     */
    protected function addMoreLinkParameter(?string $moreLink): void
    {
        $this->addParameter(static::PARAMETER_MORE_LINK, $moreLink);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'SspServiceListWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/dashboard-service/dashboard-service.twig';
    }
}
