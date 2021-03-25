import { Injectable } from '@angular/core';
import { ConcreteProductGeneratorData, IdGenerator } from './types';

@Injectable({ providedIn: 'root' })
export class ConcreteProductSkuGeneratorService implements IdGenerator {
    constructor(
        private concreteProductGeneratorData: ConcreteProductGeneratorData,
    ) {}

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
