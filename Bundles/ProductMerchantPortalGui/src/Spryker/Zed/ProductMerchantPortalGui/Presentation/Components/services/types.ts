export interface IdGenerator<T = string> {
    generate(index?: number, prevId?: T): T;
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
