import { Injectable } from '@angular/core';
import { ConcreteProductGeneratorData, IdGenerator } from './types';

@Injectable({ providedIn: 'root' })
export class ConcreteProductNameGeneratorService implements IdGenerator {
    constructor(
        private concreteProductGeneratorData: ConcreteProductGeneratorData,
    ) {}

    generate(): string {
        return this.concreteProductGeneratorData.getAbstractName();
    }
}
