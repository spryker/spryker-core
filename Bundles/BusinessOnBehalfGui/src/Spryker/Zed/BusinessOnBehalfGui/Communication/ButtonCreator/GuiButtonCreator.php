<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\ButtonCreator;

use Generated\Shared\Transfer\ButtonTransfer;
use Spryker\Service\UtilText\Model\Url\Url;

class GuiButtonCreator implements GuiButtonCreatorInterface
{
    protected const BUTTON_ATTACH_CUSTOMER_TO_COMPANY_URL = 'company-user-gui/create-company-user/attach-customer';
    protected const BUTTON_ATTACH_CUSTOMER_TO_COMPANY_TITLE = 'Attach to company';
    protected const PARAM_ID_CUSTOMER = 'id-customer';

    /**
     * @param int $idCustomer
     * @param \Generated\Shared\Transfer\ButtonTransfer[] $buttons
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function addAttachToCompanyButtonForCustomerTable(int $idCustomer, array $buttons): array
    {
        $defaultOptions = [
            'class' => 'btn-create',
            'icon' => 'fa-plus',
        ];

        $url = Url::generate(
            static::BUTTON_ATTACH_CUSTOMER_TO_COMPANY_URL,
            [
                static::PARAM_ID_CUSTOMER => $idCustomer,
            ]
        );

        $buttons[] = (new ButtonTransfer())
            ->setUrl($url)
            ->setTitle(static::BUTTON_ATTACH_CUSTOMER_TO_COMPANY_TITLE)
            ->setDefaultOptions($defaultOptions);

        return $buttons;
    }
}
