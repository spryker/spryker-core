<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\Search\SearchConstants;

class SearchConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getSearchIndexName()
    {
        return $this->get(SearchConstants::ELASTICA_PARAMETER__INDEX_NAME);
    }

    /**
     * @return string
     */
    public function getSearchDocumentType()
    {
        return $this->get(SearchConstants::ELASTICA_PARAMETER__DOCUMENT_TYPE);
    }

    /**
     * @return array
     */
    public function getElasticsearchConfig()
    {
        $config = [
            'transport' => ucfirst($this->get(SearchConstants::ELASTICA_PARAMETER__TRANSPORT)),
            'port' => $this->get(SearchConstants::ELASTICA_PARAMETER__PORT),
            'host' => $this->get(SearchConstants::ELASTICA_PARAMETER__HOST),
        ];

        if ($this->get(SearchConstants::ELASTICA_PARAMETER__AUTH_HEADER)) {
            $config['headers'] = [
                'Authorization' => 'Basic ' . $this->get(SearchConstants::ELASTICA_PARAMETER__AUTH_HEADER),
            ];
        }

        return $config;
    }

}
