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
     * @param string $contentKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentFileListTypeTransfer|null
     */
    public function executeFileListTypeByKey(string $contentKey, string $localeName): ?ContentFileListTypeTransfer
    {
        return $this->getFactory()->createContentFileListTypeMapper()->executeFileListTypeByKey($contentKey, $localeName);
    }
}
