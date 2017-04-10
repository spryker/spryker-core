<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder\Business\Api\Converter;

use FACTFinder\Adapter\Suggest as FFSuggestAdapter;
use Generated\Shared\Transfer\FactFinderSuggestResponseTransfer;

class SuggestResponseConverter extends BaseConverter
{

    /**
     * @var \FACTFinder\Adapter\Suggest
     */
    protected $suggestAdapter;

    /**
     * @param \FACTFinder\Adapter\Suggest $suggestAdapter
     */
    public function __construct(FFSuggestAdapter $suggestAdapter)
    {
        $this->suggestAdapter = $suggestAdapter;
    }

    /**
     * @return \Generated\Shared\Transfer\FactFinderSuggestResponseTransfer
     */
    public function convert()
    {
        $responseTransfer = new FactFinderSuggestResponseTransfer();

        $suggestions = $this->suggestAdapter->getSuggestions();

        foreach($suggestions as $suggestion) {
            $responseTransfer->addSuggestions([
                'imageUrl' => $suggestion->getImageUrl(),
                'label' => $suggestion->getLabel(),
                'url' => $suggestion->getUrl(),
                'attributes' => $suggestion->getAttributes(),
            ]);
        }

        return $responseTransfer;
    }

}
