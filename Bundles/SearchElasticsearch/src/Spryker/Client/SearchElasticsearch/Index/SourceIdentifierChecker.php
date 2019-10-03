<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Index;

use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\SearchElasticsearch\SearchElasticsearchConfig;

class SourceIdentifierChecker implements SourceIdentifierCheckerInterface
{
    /**
     * @var \Spryker\Client\SearchElasticsearch\SearchElasticsearchConfig
     */
    protected $config;

    /**
     * @param \Spryker\Client\SearchElasticsearch\SearchElasticsearchConfig $config
     */
    public function __construct(SearchElasticsearchConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return bool
     */
    public function isSupported(SearchContextTransfer $searchContextTransfer): bool
    {
        $sourceIdentifier = $searchContextTransfer->requireSourceIdentifier()->getSourceIdentifier();
        $supportedSourceNames = $this->config->getSupportedSourceNames();

        return in_array($sourceIdentifier, $supportedSourceNames, true);
    }
}
