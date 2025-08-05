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
    public function __construct(?SspServiceCollectionTransfer $sspServiceCollectionTransfer, ?string $moreLink = null)
    {
        $this->addParameter('totalItems', $sspServiceCollectionTransfer?->getPaginationOrFail()->getNbResults());
        $this->addParameter('services', $sspServiceCollectionTransfer?->getServices());
        $this->addParameter('moreLink', $moreLink);
    }

    public static function getName(): string
    {
        return 'ServiceListWidget';
    }

    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/service-list-widget/service-list.twig';
    }
}
