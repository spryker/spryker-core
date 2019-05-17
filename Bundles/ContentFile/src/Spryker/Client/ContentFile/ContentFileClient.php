<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentFile;

use Generated\Shared\Transfer\ContentFileListTypeTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ContentFile\ContentFileFactory getFactory()
 */
class ContentFileClient extends AbstractClient implements ContentFileClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentFileListTypeTransfer|null
     */
    public function executeContentFileListTypeById(int $idContent, string $localeName): ?ContentFileListTypeTransfer
    {
        return $this->getFactory()->createContentFileListTypeMapper()->executeFileListTypeById($idContent, $localeName);
    }
}
