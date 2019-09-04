<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Suggest;

/**
 * @deprecated Use `\Spryker\Client\SearchElasticsearch\Suggest\SuggestBuilderInterface` instead.
 */
interface SuggestBuilderInterface
{
    /**
     * @param string $name
     * @param string $field
     *
     * @return \Elastica\Suggest\Term
     */
    public function createTerm($name, $field);

    /**
     * @param string $name
     * @param string $field
     *
     * @return \Elastica\Suggest\Completion
     */
    public function createComplete($name, $field);

    /**
     * @param string $name
     * @param string $field
     *
     * @return \Elastica\Suggest\Phrase
     */
    public function createPhrase($name, $field);
}
