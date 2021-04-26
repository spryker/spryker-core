export interface ProductAttribute {
    value: string;
    name: string;
    isDisabled?: boolean;
    attributes: ProductAttributeValue[];
}

export interface ProductAttributeValue {
    value: string;
    name: string;
}
