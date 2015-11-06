<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Translation\Business;

use SprykerEngine\Zed\Translation\Business\Exception\TranslationFormatNotFoundException;
use Symfony\Component\Translation\Loader\CsvFileLoader;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\Loader\PoFileLoader;

class TranslationFileLoaderFactory
{

    /**
     * @param string $format
     *
     * @throws TranslationFormatNotFoundException
     *
     * @return LoaderInterface
     */
    public static function getLoader($format)
    {
        switch ($format) {
            case 'po':
                return new PoFileLoader();
            case 'csv':
                return new CsvFileLoader();
            default:
                throw new TranslationFormatNotFoundException(
                    sprintf(
                        'There is no loader for the format "%s".',
                        $format
                    )
                );
        }
    }

}
