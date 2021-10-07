<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Communication\Formatter;

use Generated\Shared\Transfer\CompanyCollectionTransfer;

class CompanyGuiFormatter implements CompanyGuiFormatterInterface
{
    /**
     * @var string
     */
    protected const KEY_ID = 'id';
    /**
     * @var string
     */
    protected const KEY_TEXT = 'text';

    /**
     * @param \Generated\Shared\Transfer\CompanyCollectionTransfer $companyCollectionTransfer
     *
     * @return array<string[]>
     */
    public function formatCompanyCollectionToSuggestions(CompanyCollectionTransfer $companyCollectionTransfer): array
    {
        $formattedSuggestCompanyList = [];

        foreach ($companyCollectionTransfer->getCompanies() as $companyTransfer) {
            $formattedSuggestCompanyList[] = [
                static::KEY_ID => $companyTransfer->getIdCompany(),
                static::KEY_TEXT => sprintf(
                    '%s (ID: %d)',
                    $companyTransfer->getName(),
                    $companyTransfer->getIdCompany()
                ),
            ];
        }

        return $formattedSuggestCompanyList;
    }
}
