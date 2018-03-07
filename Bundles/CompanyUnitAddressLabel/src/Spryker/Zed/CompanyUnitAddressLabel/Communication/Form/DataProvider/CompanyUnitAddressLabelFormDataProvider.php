<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Communication\Form\DataProvider;

use Spryker\Zed\CompanyUnitAddressLabel\Communication\Form\CompanyUnitAddressLabelChoiceFormType;
use Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepositoryInterface;

class CompanyUnitAddressLabelFormDataProvider
{
    /**
     * @var \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepositoryInterface
     */
    protected $companyUnitAddressLabelRepository;

    /**
     * @param \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepositoryInterface $companyUnitAddressLabelRepository
     */
    public function __construct(
        CompanyUnitAddressLabelRepositoryInterface $companyUnitAddressLabelRepository
    ) {
        $this->companyUnitAddressLabelRepository = $companyUnitAddressLabelRepository;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            CompanyUnitAddressLabelChoiceFormType::OPTION_VALUES_CHOICES => $this->getChoices(),
            'label' => false,
        ];
    }

    //TODO: use \Spryker\Zed\CmsBlockProductConnector\Communication\Plugin\CmsBlockProductAbstractFormPlugin::buildForm
    /**
     * @param int|null $idCompanyUnitAddress
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer
     */
    public function getData($idCompanyUnitAddress = null)
    {
        return $this->companyUnitAddressLabelRepository
            ->findCompanyUnitAddressLabelsByAddress($idCompanyUnitAddress);
    }

    //TODO:rename choices to labelChoices
    /**
     * @return array
     */
    protected function getChoices()
    {
        $labelCollection = $this->companyUnitAddressLabelRepository->findCompanyUnitAddressLabels();

        $result = [];
        foreach ($labelCollection->getLabels() as $label) {
            $result[$label->getName()] = $label->getIdCompanyUnitAddressLabel();
        }

        return $result;
    }
}
