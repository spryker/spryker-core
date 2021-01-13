export interface BulkEditProductVariantSections {
  status: BulkEditProductVariantSectionStatus;
  validity: BulkEditProductVariantSectionValidity;
}

export interface BulkEditProductVariantSection {
  title: string;
  activationName: string;
  name: unknown;
  placeholder: unknown;
}

export interface BulkEditProductVariantSectionStatus
  extends BulkEditProductVariantSection {
  name: string;
  placeholder: string;
}

export interface BulkEditProductVariantSectionValidity
  extends BulkEditProductVariantSection {
  name: BulkEditProductVariantSectionValidityRange;
  placeholder: BulkEditProductVariantSectionValidityRange;
}

export interface BulkEditProductVariantSectionValidityRange {
  from: string;
  to: string;
}
