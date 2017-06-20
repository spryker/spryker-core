<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlock;

interface CmsBlockClientInterface
{

    /**
     * @api
     *
     * @param string[] $blockNames
     * @param string $localeName
     *
     * @return array
     */
    public function findBlocksByNames($blockNames, $localeName);

    /**
     * @api
     *
     * @param array $options
     * @param string $localName
     *
     * @return array
     */
    public function findBlockNamesByOptions(array $options, $localName);

}
