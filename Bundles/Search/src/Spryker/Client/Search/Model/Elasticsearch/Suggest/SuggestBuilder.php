<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Suggest;

use Elastica\Suggest\Completion;
use Elastica\Suggest\Phrase;
use Elastica\Suggest\Term;

/**
 * @deprecated Use `\Spryker\Client\SearchElasticsearch\Suggest\SuggestBuilder` instead.
 */
class SuggestBuilder implements SuggestBuilderInterface
{
    /**
     * @param string $name
     * @param string $field
     *
     * @return \Elastica\Suggest\Term
     */
    public function createTerm($name, $field)
    {
        return new Term($name, $field);
    }

    /**
     * @param string $name
     * @param string $field
     *
     * @return \Elastica\Suggest\Completion
     */
    public function createComplete($name, $field)
    {
        return new Completion($name, $field);
    }

    /**
     * @param string $name
     * @param string $field
     *
     * @return \Elastica\Suggest\Phrase
     */
    public function createPhrase($name, $field)
    {
        return new Phrase($name, $field);
    }
}
