<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Form\DataProvider;

use Generated\Shared\Transfer\UserTransfer;

class MerchantPortalTypeSelectionFormDataProvider extends TypeSelectionFormDataProvider
{
    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return array<string, mixed>
     */
    public function getOptions(UserTransfer $userTransfer): array
    {
        return array_merge(parent::getOptions($userTransfer), [
            'form_selector' => null,
            'is_login' => false,
            'ajax_form_selector' => null,
        ]);
    }
}
