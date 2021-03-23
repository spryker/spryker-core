import { ProductAttributeValue } from '../product-attributes-selector/types';

export interface ConcreteProductPreview {
    name: string;
    sku: string;
    superAttributes: ProductAttributeValue[];
}
