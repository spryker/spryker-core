export interface BulkEditProductVariantSections {
    status: BulkEditProductVariantSectionStatus;
    validity: BulkEditProductVariantSectionValidity;
}

export interface BulkEditProductVariantSection {
    title: string;
    activationName: string;
    name: unknown;
    placeholder: unknown;
    value?: unknown;
    error?: string;
}

export interface BulkEditProductVariantSectionStatus extends BulkEditProductVariantSection {
    name: string;
    placeholder: string;
    value?: boolean;
}

export interface BulkEditProductVariantSectionValidity extends BulkEditProductVariantSection {
    name: BulkEditProductVariantSectionValidityRange;
    placeholder: BulkEditProductVariantSectionValidityRange;
    value?: BulkEditProductVariantSectionValidityRange;
}

export interface BulkEditProductVariantSectionValidityRange {
    from: string;
    to: string;
}
