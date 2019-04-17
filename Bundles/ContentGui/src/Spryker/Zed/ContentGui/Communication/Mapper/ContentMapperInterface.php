<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Mapper;

interface ContentMapperInterface
{
    /**
     * @param string[] $contentTypes
     *
     * @return string[][]
     */
    public function mapEditorContentTypes(array $contentTypes): array;
}
