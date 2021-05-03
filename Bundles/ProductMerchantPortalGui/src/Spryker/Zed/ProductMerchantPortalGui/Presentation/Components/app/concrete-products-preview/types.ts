import { ProductAttributeValue } from '../product-attributes-selector/types';

export interface ConcreteProductPreview {
    name: string;
    sku: string;
    superAttributes: ConcreteProductPreviewSuperAttribute[];
}

interface ConcreteProductPreviewSuperAttribute {
    value: string;
    name: string;
    attribute: ProductAttributeValue;
}

export interface ConcreteProductPreviewErrors {
    fields?: {
        sku: string;
        name: string;
    };
    errors?: {
        sku: string;
        name: string;
    };
}
