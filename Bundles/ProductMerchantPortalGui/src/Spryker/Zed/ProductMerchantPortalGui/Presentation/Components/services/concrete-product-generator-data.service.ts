import { Injectable } from '@angular/core';
import { ConcreteProductGeneratorData } from './types';

@Injectable({ providedIn: 'root' })
export abstract class ConcreteProductGeneratorDataService implements ConcreteProductGeneratorData {
    abstract getAbstractName(): string;
    abstract getAbstractSku(): string;
}
