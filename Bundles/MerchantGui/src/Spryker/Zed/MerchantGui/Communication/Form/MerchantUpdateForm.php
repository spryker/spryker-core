<?php
/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantGui\Communication\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class MerchantUpdateForm extends MerchantForm
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param int|null $currentMerchantId
     *
     * @return $this
     */
    protected function addMerchantReferenceField(FormBuilderInterface $builder, ?int $currentMerchantId = null)
    {
        $builder
            ->add(static::FIELD_MERCHANT_REFERENCE, TextType::class, [
                'label' => static::LABEL_MERCHANT_REFERENCE,
                'attr' => [
                    'readonly' => 'readonly',
                ],
            ]);

        return $this;
    }
}
