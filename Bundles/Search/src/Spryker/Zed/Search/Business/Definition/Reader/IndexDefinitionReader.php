<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Definition\Reader;

use Spryker\Zed\Search\Dependency\Service\SearchToUtilEncodingInterface;
use Symfony\Component\Finder\SplFileInfo;

class IndexDefinitionReader implements IndexDefinitionReaderInterface
{
    /**
     * @var \Spryker\Zed\Search\Dependency\Service\SearchToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\Search\Dependency\Service\SearchToUtilEncodingInterface $utilEncodingService
     */
    public function __construct(SearchToUtilEncodingInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @return array
     */
    public function read(SplFileInfo $fileInfo): array
    {
        return $this->utilEncodingService->decodeJson($fileInfo->getContents(), true);
    }
}
