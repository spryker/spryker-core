<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\File\NameGenerator;


use Generated\Shared\Transfer\LocaleTransfer;

class CsvNameGenerator implements NameGeneratorInterface
{

    /**
     * @var string
     */
    protected $type;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @param string $type
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     */
    public function __construct($type, LocaleTransfer $localeTransfer)
    {
        $this->type = $type;
        $this->localeTransfer = $localeTransfer;
    }

    /**
     * @return string
     */
    public function generateFileName()
    {
        return $this->type . '_' . $this->localeTransfer->getLocaleName() . '.csv';
    }
}
