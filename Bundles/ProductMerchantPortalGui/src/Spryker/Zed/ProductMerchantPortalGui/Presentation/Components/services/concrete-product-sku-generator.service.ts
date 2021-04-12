import { Injectable } from '@angular/core';
import { IdGenerator } from './types';
import { ConcreteProductGeneratorDataService } from './concrete-product-generator-data.service';

@Injectable()
export class ConcreteProductSkuGeneratorService implements IdGenerator {
    constructor(private concreteProductGeneratorData: ConcreteProductGeneratorDataService) {}

    generate(prevId?: string): string {
        const abstractSku = this.concreteProductGeneratorData.getAbstractSku();
        let id;

        if (!prevId) {
            id = 1;
        } else {
            id = Number(prevId.split('-')[1]);
            id++;
        }

        return `${abstractSku}-${id}`;
    }
}
