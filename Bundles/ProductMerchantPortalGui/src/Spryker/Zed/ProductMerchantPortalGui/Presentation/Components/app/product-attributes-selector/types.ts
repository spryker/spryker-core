export interface ProductAttribute {
    value: string;
    name: string;
    values: ProductAttributeValue[];
}

export interface ProductAttributeValue {
    value: string;
    name: string;
}
