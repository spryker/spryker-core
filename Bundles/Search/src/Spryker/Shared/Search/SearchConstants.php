<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Search;

interface SearchConstants
{
    /**
     * When executing boosted full text search queries the value of this config setting will be used as the boost factor.
     * I.e. to set the boost factor to 3 add this to your config: `$config[SearchConstants::FULL_TEXT_BOOSTED_BOOSTING_VALUE] = 3;`.
     *
     * @api
     */
    const FULL_TEXT_BOOSTED_BOOSTING_VALUE = 'FULL_TEXT_BOOSTED_BOOSTING_VALUE';

    /**
     * @api
     */
    const FACET_NAME_AGGREGATION_SIZE = 'FACET_NAME_AGGREGATION_SIZE';

    /**
     * Elasticsearch connection host name. (Required)
     *
     * @api
     */
    const ELASTICA_PARAMETER__HOST = 'ELASTICA_PARAMETER__HOST';

    /**
     * Elasticsearch connection port number. (Required)
     *
     * @api
     */
    const ELASTICA_PARAMETER__PORT = 'ELASTICA_PARAMETER__PORT';

    /**
     * Elasticsearch connection transport name (i.e. "http"). (Required)
     *
     * @api
     */
    const ELASTICA_PARAMETER__TRANSPORT = 'ELASTICA_PARAMETER__TRANSPORT';

    /**
     * Elasticsearch connection index name. (Required)
     *
     * @api
     */
    const ELASTICA_PARAMETER__INDEX_NAME = 'ELASTICA_PARAMETER__INDEX_NAME';

    /**
     * Elasticsearch connection document type. (Required)
     *
     * @api
     */
    const ELASTICA_PARAMETER__DOCUMENT_TYPE = 'ELASTICA_PARAMETER__DOCUMENT_TYPE';

    /**
     * Elasticsearch connection authentication header parameters. (Optional)
     *
     * @api
     */
    const ELASTICA_PARAMETER__AUTH_HEADER = 'ELASTICA_PARAMETER__AUTH_HEADER';

    /**
     * Specification:
     * - Defines an array of extra Elasticsearch connection parameters (i.e. ['foo' => 'bar', ...]). (Optional)
     *
     * @api
     */
    const ELASTICA_PARAMETER__EXTRA = 'ELASTICA_PARAMETER__EXTRA';

    /**
     * Specification:
     * - Defines a suffix string for the index name to be installed. (Optional)
     *
     * @api
     */
    const SEARCH_INDEX_NAME_SUFFIX = 'SEARCH_INDEX_NAME_SUFFIX';

    /**
     * Specification:
     * - Defines a custom configuration for \Elastica\Client.
     * - This configuration is used exclusively when set, e.g. no other Elastica configuration will be used for the client.
     * - @see http://elastica.io/ for details.
     *
     * @api
     */
    const ELASTICA_CLIENT_CONFIGURATION = 'ELASTICA_CLIENT_CONFIGURATION';
}
