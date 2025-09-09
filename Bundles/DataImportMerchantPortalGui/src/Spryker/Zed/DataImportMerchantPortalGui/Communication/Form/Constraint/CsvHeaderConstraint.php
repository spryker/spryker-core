<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchantPortalGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class CsvHeaderConstraint extends SymfonyConstraint
{
    /**
     * @var array<string, list<string>>
     */
    public array $headers = [];

    /**
     * @var string
     */
    public string $message = 'The following headers are not recognized and will be ignored during import: %headers%.';

    /**
     * @param array<string, mixed>|null $options
     * @param list<string>|null $groups
     * @param mixed $payload
     */
    public function __construct(?array $options = null, ?array $groups = null, $payload = null)
    {
        parent::__construct($options, $groups, $payload);
    }

    /**
     * @return list<string>
     */
    public function getRequiredOptions(): array
    {
        return ['headers'];
    }
}
