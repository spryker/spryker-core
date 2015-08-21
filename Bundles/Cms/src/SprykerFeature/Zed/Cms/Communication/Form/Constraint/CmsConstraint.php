<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class CmsConstraint
{
    public static function getMandatoryConstraints()
    {
        return [
            new Required(),
            new NotBlank(),
            new Length(['max' => 256]),
        ];
    }
}
