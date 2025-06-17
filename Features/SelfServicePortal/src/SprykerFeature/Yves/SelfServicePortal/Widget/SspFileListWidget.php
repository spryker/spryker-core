<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

class SspFileListWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\FileAttachmentFileCollectionTransfer|null $fileAttachmentFileCollectionTransfer
     * @param string|null $moreLink
     */
    public function __construct(?FileAttachmentFileCollectionTransfer $fileAttachmentFileCollectionTransfer, ?string $moreLink = null)
    {
        $this->addParameter('totalItems', $fileAttachmentFileCollectionTransfer?->getPagination()?->getNbResults());
        $this->addParameter('fileAttachments', $fileAttachmentFileCollectionTransfer?->getFileAttachments());
        $this->addParameter('moreLink', $moreLink);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'SspFileListWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/dashboard-file/dashboard-file.twig';
    }
}
