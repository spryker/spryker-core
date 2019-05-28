<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentFile\Mapper;

use Generated\Shared\Transfer\ContentFileListTypeTransfer;

interface ContentFileListTypeMapperInterface
{
    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @throws \Spryker\Client\ContentFile\Exception\InvalidFileListTermException
     *
     * @return \Generated\Shared\Transfer\ContentFileListTypeTransfer|null
     */
    public function executeFileListTypeById(int $idContent, string $localeName): ?ContentFileListTypeTransfer;
}
