<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Exporter;

use Symfony\Component\HttpFoundation\StreamedResponse;

interface PriceProductScheduleCsvExporterInterface
{
    /**
     * @param int $idPriceProductScheduleList
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportToCsvFile(int $idPriceProductScheduleList): StreamedResponse;
}
