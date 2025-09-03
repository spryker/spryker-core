<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor;

use Symfony\Component\Form\FormInterface;

interface CsvImportProcessorInterface
{
    /**
     * @param string $scope
     * @param \Symfony\Component\Form\FormInterface $form
     * @param int $idFile
     *
     * @return array<string, mixed>
     */
    public function processCsvImportsForScope(string $scope, FormInterface $form, int $idFile): array;
}
