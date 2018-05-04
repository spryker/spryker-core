<?php
/**
 * Created by PhpStorm.
 * User: khatsko
 * Date: 5/4/18
 * Time: 7:41 AM
 */

namespace Spryker\Service\GetItNow\Plugin;


use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;
use Spryker\Service\Kernel\AbstractPlugin;

class EANBarcodeGenerator extends AbstractPlugin implements BarcodeGeneratorPluginInterface
{
    /**
     * @param string $text
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generate(string $text): BarcodeResponseTransfer
    {
        $barcodeResponseTransfer = new BarcodeResponseTransfer();
        $barcode = new BarcodeGenerator();
        $barcode->setText($text);
        $barcode->setType(BarcodeGenerator::Ean128);
        $code = $barcode->generate();

        $barcodeResponseTransfer
            ->setCode($code)
            ->setEncoding('EAN');

        return $barcodeResponseTransfer;
    }

}
