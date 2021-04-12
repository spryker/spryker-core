import { Injectable } from '@angular/core';
import { IdGenerator } from './types';
import { ConcreteProductGeneratorDataService } from './concrete-product-generator-data.service';

@Injectable()
export class ConcreteProductNameGeneratorService implements IdGenerator {
    constructor(private concreteProductGeneratorData: ConcreteProductGeneratorDataService) {}

    generate(): string {
        return this.concreteProductGeneratorData.getAbstractName();
    }
}
