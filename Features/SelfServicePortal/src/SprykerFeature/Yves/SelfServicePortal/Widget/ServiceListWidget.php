<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Generated\Shared\Transfer\SspServiceCollectionTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

class ServiceListWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\SspServiceCollectionTransfer|null $sspServiceCollectionTransfer
     * @param string|null $moreLink
     */
    public function __construct(?SspServiceCollectionTransfer $sspServiceCollectionTransfer, ?string $moreLink = null)
    {
        $this->addParameter('totalItems', $sspServiceCollectionTransfer?->getPaginationOrFail()->getNbResults());
        $this->addParameter('services', $sspServiceCollectionTransfer?->getServices());
        $this->addParameter('moreLink', $moreLink);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ServiceListWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/service-list-widget/service-list.twig';
    }
}
