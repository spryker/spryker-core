<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor;

use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor\Strategy\CsvImportStrategyInterface;
use Symfony\Component\Form\FormInterface;

class CsvImportProcessor implements CsvImportProcessorInterface
{
    /**
     * @param array<\SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor\Strategy\CsvImportStrategyInterface> $csvImportStrategies
     */
    public function __construct(protected array $csvImportStrategies)
    {
    }

    /**
     * @param string $scope
     * @param \Symfony\Component\Form\FormInterface $form
     * @param int $idFile
     *
     * @return array<string, mixed>
     */
    public function processCsvImportsForScope(string $scope, FormInterface $form, int $idFile): array
    {
        $strategy = $this->findStrategyForScope($scope);
        if (!$strategy) {
            return [];
        }

        $uploadedFile = $strategy->extractFileFromForm($form);
        if (!$uploadedFile) {
            return [];
        }

        return $strategy->processCsvFile($uploadedFile, $idFile);
    }

    protected function findStrategyForScope(string $scope): ?CsvImportStrategyInterface
    {
        foreach ($this->csvImportStrategies as $strategy) {
            if ($strategy->canHandle($scope)) {
                return $strategy;
            }
        }

        return null;
    }
}
