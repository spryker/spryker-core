import { Injectable } from '@angular/core';
import { ConcreteProductGeneratorData } from './types';

@Injectable()
export abstract class ConcreteProductGeneratorDataService implements ConcreteProductGeneratorData {
    abstract getAbstractName(): string;
    abstract getAbstractSku(): string;
}
