<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode\Dependency\Plugin;

use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;
use Spryker\Service\Kernel\AbstractPlugin;

/**
 * TODO: This is a test code, it will be changed or removed in future
 *
 * @method \Spryker\Service\Barcode\BarcodeServiceFactory getFactory()
 */
class EanPlugin extends AbstractPlugin implements BarcodeGeneratorPluginInterface
{
    protected const DUMMY_CODE = 'EAN_DUMMY_CODE';
    protected const EAN_ENCODING = 'EAN';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $text
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generate(string $text): BarcodeResponseTransfer
    {
        $transfer = new BarcodeResponseTransfer();

        return $transfer->setCode(static::DUMMY_CODE)
            ->setEncoding(static::EAN_ENCODING);
    }
}
