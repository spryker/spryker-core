<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundlePageSearch\Plugin\Elasticsearch\ResultFormatter;

use ArrayObject;
use Elastica\ResultSet;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\AbstractElasticsearchResultFormatterPlugin;

class ConfigurableBundleTemplatePageSearchResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{
    public const NAME = 'ConfigurableBundleTemplatePageSearchResultFormatterPlugin';

    /**
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array $requestParameters
     *
     * @return array
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters): array
    {
        $configurableBundleTemplateTransfers = [];

        foreach ($searchResult->getResults() as $document) {
            $configurableBundleTemplateTransfers[] = $this->mapDataToConfigurableBundleTemplateTransfer(
                $document->getSource()[PageIndexMap::SEARCH_RESULT_DATA]
            );
        }

        return $configurableBundleTemplateTransfers;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    protected function mapDataToConfigurableBundleTemplateTransfer(array $data): ConfigurableBundleTemplateTransfer
    {
        $configurableBundleTemplateTransfer = new ConfigurableBundleTemplateTransfer();
        $configurableBundleTemplateTransfer->fromArray($data, true);
        $configurableBundleTemplateTransfer->setTranslations(new ArrayObject([
            (new ConfigurableBundleTemplateTranslationTransfer())->fromArray($data[ConfigurableBundleTemplatePageSearchTransfer::TRANSLATIONS], true),
        ]));

        return $configurableBundleTemplateTransfer;
    }
}
