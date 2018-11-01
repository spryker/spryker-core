<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
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
            CompanyUnitAddressLabelChoiceFormType::OPTION_VALUES_LABEL_CHOICES => $this->getLabelChoices(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function getData(CompanyUnitAddressTransfer $companyUnitAddressTransfer)
    {
        if (!$companyUnitAddressTransfer->getIdCompanyUnitAddress()) {
            $companyUnitAddressTransfer->setLabelCollection($this->getEmptyAddressLabelCollection());

            return $companyUnitAddressTransfer;
        }
        $labelCollection = $this->companyUnitAddressLabelRepository
            ->findCompanyUnitAddressLabelsByAddress($companyUnitAddressTransfer->getIdCompanyUnitAddress());
        $companyUnitAddressTransfer->setLabelCollection($labelCollection);

        return $companyUnitAddressTransfer;
    }

    /**
     * @return array
     */
    protected function getLabelChoices()
    {
        $labelCollection = $this->companyUnitAddressLabelRepository->findCompanyUnitAddressLabels();

        $result = [];
        foreach ($labelCollection->getLabels() as $label) {
            $result[$label->getName()] = $label->getIdCompanyUnitAddressLabel();
        }

        return $result;
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer
     */
    protected function getEmptyAddressLabelCollection(): CompanyUnitAddressLabelCollectionTransfer
    {
        $labelCollection = new CompanyUnitAddressLabelCollectionTransfer();
        $labelCollection->setLabels(new ArrayObject());

        return $labelCollection;
    }
}
