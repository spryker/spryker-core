<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Suggest;

use Elastica\Suggest\Completion;
use Elastica\Suggest\Phrase;
use Elastica\Suggest\Term;

class SuggestBuilder implements SuggestBuilderInterface
{
    /**
     * @param string $name
     * @param string $field
     *
     * @return \Elastica\Suggest\Term
     */
    public function createTerm(string $name, string $field): Term
    {
        return new Term($name, $field);
    }

    /**
     * @param string $name
     * @param string $field
     *
     * @return \Elastica\Suggest\Completion
     */
    public function createComplete(string $name, string $field): Completion
    {
        return new Completion($name, $field);
    }

    /**
     * @param string $name
     * @param string $field
     *
     * @return \Elastica\Suggest\Phrase
     */
    public function createPhrase(string $name, string $field): Phrase
    {
        return new Phrase($name, $field);
    }
}
