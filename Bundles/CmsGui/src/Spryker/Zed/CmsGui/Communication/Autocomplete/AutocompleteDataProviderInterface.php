<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Autocomplete;

interface AutocompleteDataProviderInterface
{
    /**
     * @param string $translationKey
     *
     * @return array
     */
    public function getAutocompleteDataForTranslationKey($translationKey);

    /**
     * @param string $translationValue
     *
     * @return array
     */
    public function getAutocompleteDataForTranslationValue($translationValue);
}
