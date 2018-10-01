<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\CustomerExtension\Dependency\Plugin\CustomerTableActionPluginInterface;

class AttachUserToCompanyPlugin implements CustomerTableActionPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param string[] $buttons
     *
     * @return string[]
     */
    public function execute(CustomerTransfer $customerTransfer, array $buttons): array
    {
        $buttons[] = '<a href="/product-attribute-gui/view/productAbstract?id-product-abstract=219" class="btn btn-xs btn-outline  btn-edit"><i class="fa fa-pencil-square-o"></i> Attach to company</a>';

        return $buttons;
    }
}
