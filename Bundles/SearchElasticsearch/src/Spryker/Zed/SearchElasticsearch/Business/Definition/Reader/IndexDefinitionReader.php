<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Definition\Reader;

use Spryker\Zed\SearchElasticsearch\Dependency\Service\SearchElasticsearchToUtilEncodingServiceInterface;
use Symfony\Component\Finder\SplFileInfo;

class IndexDefinitionReader implements IndexDefinitionReaderInterface
{
    /**
     * @var \Spryker\Zed\SearchElasticsearch\Dependency\Service\SearchElasticsearchToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\SearchElasticsearch\Dependency\Service\SearchElasticsearchToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(SearchElasticsearchToUtilEncodingServiceInterface $utilEncodingService)
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
