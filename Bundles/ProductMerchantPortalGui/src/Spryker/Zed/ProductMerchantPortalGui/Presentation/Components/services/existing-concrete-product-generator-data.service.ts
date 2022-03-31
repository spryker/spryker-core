import { Injectable } from '@angular/core';
import { ExistingConcreteProductGeneratorData, ConcreteProductPreview } from './types';
import { ConcreteProductGeneratorDataService } from './concrete-product-generator-data.service';

@Injectable()
export abstract class ExistingConcreteProductGeneratorDataService
    extends ConcreteProductGeneratorDataService
    implements ExistingConcreteProductGeneratorData
{
    abstract getExistingProducts(): ConcreteProductPreview[];
}
