export interface IdGenerator<T = string> {
    generate(prevId?: T): T;
}

export interface ConcreteProductGeneratorData {
    getAbstractName(): string;
    getAbstractSku(): string;
}

export interface ConcreteProductSkuGeneratorFactory {
    create(): IdGenerator;
}

export interface ConcreteProductNameGeneratorFactory {
    create(): IdGenerator;
}

export interface ConcreteProductPreview {
    name: string;
    sku: string;
    superAttributes: ConcreteProductPreviewSuperAttribute[];
}

export interface ConcreteProductPreviewSuperAttribute {
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

export interface AttributeOptions {
    value: string;
    title: string;
    isDisabled?: boolean;
}
