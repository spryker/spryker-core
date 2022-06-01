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
     * @var \Spryker\Zed\CompanyGui\Communication\Formatter\CompanyNameFormatterInterface
     */
    protected $companyNameFormatter;

    /**
     * @param \Spryker\Zed\CompanyGui\Communication\Formatter\CompanyNameFormatterInterface $companyNameFormatter
     */
    public function __construct(CompanyNameFormatterInterface $companyNameFormatter)
    {
        $this->companyNameFormatter = $companyNameFormatter;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyCollectionTransfer $companyCollectionTransfer
     *
     * @return array<array<string, mixed>>
     */
    public function formatCompanyCollectionToSuggestions(CompanyCollectionTransfer $companyCollectionTransfer): array
    {
        $formattedSuggestCompanyList = [];

        foreach ($companyCollectionTransfer->getCompanies() as $companyTransfer) {
            $formattedSuggestCompanyList[] = [
                static::KEY_ID => $companyTransfer->getIdCompany(),
                static::KEY_TEXT => $this->companyNameFormatter->formatName($companyTransfer),
            ];
        }

        return $formattedSuggestCompanyList;
    }
}
