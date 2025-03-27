<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspFileManagement\Widget;

use Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

class DashboardFileWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer|null $fileAttachmentFileCollectionTransfer
     */
    public function __construct(?FileAttachmentFileCollectionTransfer $fileAttachmentFileCollectionTransfer)
    {
        $this->addParameter('totalItems', $fileAttachmentFileCollectionTransfer?->getPagination()?->getNbResults());
        $this->addParameter('files', $fileAttachmentFileCollectionTransfer?->getFiles());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'DashboardFileWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SspFileManagement/views/dashboard-file/dashboard-file.twig';
    }
}
