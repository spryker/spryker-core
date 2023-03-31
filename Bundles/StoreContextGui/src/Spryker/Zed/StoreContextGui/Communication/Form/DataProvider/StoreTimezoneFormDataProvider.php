<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextGui\Communication\Form\DataProvider;

class StoreTimezoneFormDataProvider
{
    /**
     * @uses \Spryker\Zed\StoreContextGui\Communication\Form\StoreTimezoneForm::OPTION_TIMEZONE_CHOICES
     *
     * @var string
     */
    protected const OPTION_TIMEZONE_CHOICES = 'timezone_list';

    /**
     * @return array<string, array<string, string>>
     */
    public function getOptions(): array
    {
        return [
            static::OPTION_TIMEZONE_CHOICES => array_combine(
                timezone_identifiers_list(),
                timezone_identifiers_list(),
            ),
        ];
    }
}
