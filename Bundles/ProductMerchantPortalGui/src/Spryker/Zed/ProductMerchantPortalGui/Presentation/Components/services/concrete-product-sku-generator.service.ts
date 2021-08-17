import { Injectable } from '@angular/core';
import { IdGenerator } from './types';
import { ConcreteProductGeneratorDataService } from './concrete-product-generator-data.service';

@Injectable()
export class ConcreteProductSkuGeneratorService implements IdGenerator {
    constructor(private concreteProductGeneratorData: ConcreteProductGeneratorDataService) {}

    generate(prevId?: string): string {
        const abstractSku = this.concreteProductGeneratorData.getAbstractSku();
        const id = prevId ? Number(prevId.split('-').pop()) + 1 : 1;

        return `${abstractSku}-${id}`;
    }
}
