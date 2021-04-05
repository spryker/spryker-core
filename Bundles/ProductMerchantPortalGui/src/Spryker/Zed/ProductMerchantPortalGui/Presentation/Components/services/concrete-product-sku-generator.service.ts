import { Injectable } from '@angular/core';
import { IdGenerator } from './types';
import { ConcreteProductGeneratorDataService } from './concrete-product-generator-data.service';

@Injectable()
export class ConcreteProductSkuGeneratorService implements IdGenerator {
    constructor(private concreteProductGeneratorData: ConcreteProductGeneratorDataService) {}

    generate(index?: number, prevId?: string): string {
        const abstractSku = this.concreteProductGeneratorData.getAbstractSku();
        let id;

        if (!prevId) {
            id = index;
        } else {
            id = Number(prevId.split('-')[1]);
            id++;
        }

        return `${abstractSku}-${id}`;
    }
}
