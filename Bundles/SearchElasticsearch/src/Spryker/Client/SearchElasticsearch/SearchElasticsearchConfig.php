<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\SearchElasticsearch\SearchElasticsearchConstants;

class SearchElasticsearchConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getClientConfig(): array
    {
        if ($this->getConfig()->hasValue(SearchElasticsearchConstants::CLIENT_CONFIGURATION)) {
            return $this->get(SearchElasticsearchConstants::CLIENT_CONFIGURATION);
        }

        if ($this->getConfig()->hasValue(SearchElasticsearchConstants::EXTRA)) {
            $config = $this->get(SearchElasticsearchConstants::EXTRA);
        }

        $config['transport'] = ucfirst($this->get(SearchElasticsearchConstants::TRANSPORT));
        $config['port'] = $this->get(SearchElasticsearchConstants::PORT);
        $config['host'] = $this->get(SearchElasticsearchConstants::HOST);

        if ($this->getConfig()->hasValue(SearchElasticsearchConstants::AUTH_HEADER)) {
            $config['headers'] = [
                'Authorization' => 'Basic ' . $this->get(SearchElasticsearchConstants::AUTH_HEADER),
            ];
        }

        return $config;
    }
}
