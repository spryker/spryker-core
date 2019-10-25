<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\LabelCreator;

use Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Spryker\Zed\MerchantProfileGui\Dependency\Facade\MerchantProfileGuiToMerchantProfileFacadeInterface;
use Twig\Environment;

class MerchantProfileActiveLabelCreator implements MerchantProfileActiveLabelCreatorInterface
{
    /**
     * @var \Spryker\Zed\MerchantProfileGui\Dependency\Facade\MerchantProfileGuiToMerchantProfileFacadeInterface
     */
    protected $merchantProfileFacade;

    /**
     * @var \Twig\Environment
     */
    protected $twigEnvironment;

    /**
     * @param \Spryker\Zed\MerchantProfileGui\Dependency\Facade\MerchantProfileGuiToMerchantProfileFacadeInterface $merchantProfileFacade
     * @param \Twig\Environment $twigEnvironment
     */
    public function __construct(
        MerchantProfileGuiToMerchantProfileFacadeInterface $merchantProfileFacade,
        Environment $twigEnvironment
    ) {
        $this->merchantProfileFacade = $merchantProfileFacade;
        $this->twigEnvironment = $twigEnvironment;
    }

    /**
     * @param int $idMerchant
     *
     * @return string
     */
    public function getActiveLabel(int $idMerchant): string
    {
        $merchantProfileTransfer = $this->findMerchantProfileByIdMerchant($idMerchant);

        if ($merchantProfileTransfer && $merchantProfileTransfer->getIsActive()) {
            return $this->generateLabel('Active', 'label-info');
        }

        return $this->generateLabel('Inactive', 'label-danger');
    }

    /**
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer|null
     */
    protected function findMerchantProfileByIdMerchant(int $idMerchant): ?MerchantProfileTransfer
    {
        $merchantProfileCriteriaFilterTransfer = new MerchantProfileCriteriaFilterTransfer();
        $merchantProfileCriteriaFilterTransfer->setFkMerchant($idMerchant);
        $merchantProfileTransfer = $this->merchantProfileFacade
            ->findOne($merchantProfileCriteriaFilterTransfer);

        return $merchantProfileTransfer;
    }

    /**
     * @param string $title
     * @param string|null $class
     *
     * @return string
     */
    protected function generateLabel(string $title, ?string $class): string
    {
        return $this->twigEnvironment->render('label.twig', [
            'title' => $title,
            'class' => $class,
        ]);
    }
}
