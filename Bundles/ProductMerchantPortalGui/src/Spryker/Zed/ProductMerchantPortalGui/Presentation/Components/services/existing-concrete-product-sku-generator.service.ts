import { Injectable } from '@angular/core';
import { IdGenerator } from './types';
import { ExistingConcreteProductGeneratorDataService } from './existing-concrete-product-generator-data.service';
import { ConcreteProductSkuGeneratorService } from './concrete-product-sku-generator.service';

@Injectable()
export class ExistingConcreteProductSkuGeneratorService extends ConcreteProductSkuGeneratorService
    implements IdGenerator {
    constructor(private existingConcreteProductGeneratorDataService: ExistingConcreteProductGeneratorDataService) {
        super(existingConcreteProductGeneratorDataService);
    }

    generate(prevId?: string): string {
        const existingProducts = this.existingConcreteProductGeneratorDataService.getExistingProducts();
        let sku = super.generate(prevId);

        if (!existingProducts) {
            return sku;
        }

        while (existingProducts.some((product) => product.sku === sku)) {
            sku = super.generate(sku);
        }

        return sku;
    }
}
