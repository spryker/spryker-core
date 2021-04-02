export interface ProductAttribute {
    value: string;
    title: string;
    isDisabled?: boolean;
    values: ProductAttributeValue[];
}

export interface ProductAttributeValue {
    value: string;
    title: string;
}
