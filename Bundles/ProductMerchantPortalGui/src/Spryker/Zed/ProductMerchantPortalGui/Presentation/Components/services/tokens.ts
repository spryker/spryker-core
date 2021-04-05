import { InjectionToken, StaticProvider } from '@angular/core';
import { IdGenerator } from './types';
import { ConcreteProductSkuGeneratorService } from './concrete-product-sku-generator.service';
import { ConcreteProductNameGeneratorService } from './concrete-product-name-generator.service';
import { ConcreteProductGeneratorDataService } from './concrete-product-generator-data.service';

export const ConcreteProductSkuGeneratorToken = new InjectionToken<IdGenerator>('ConcreteProductSkuGeneratorToken');

export const ConcreteProductSkuGeneratorProviderToken = new InjectionToken<StaticProvider>(
    'ConcreteProductSkuGeneratorProviderToken',
    {
        providedIn: 'root',
        factory: provideConcreteProductSkuGenerator,
    },
);

export function provideConcreteProductSkuGenerator(): StaticProvider {
    return {
        provide: ConcreteProductSkuGeneratorToken,
        useClass: ConcreteProductSkuGeneratorService,
        deps: [ConcreteProductGeneratorDataService],
    };
}

export const ConcreteProductNameGeneratorToken = new InjectionToken<IdGenerator>('ConcreteProductNameGeneratorToken');

export const ConcreteProductNameGeneratorProviderToken = new InjectionToken<StaticProvider>(
    'ConcreteProductNameGeneratorProviderToken',
    {
        providedIn: 'root',
        factory: provideConcreteProductNameGenerator,
    },
);

export function provideConcreteProductNameGenerator(): StaticProvider {
    return {
        provide: ConcreteProductNameGeneratorToken,
        useClass: ConcreteProductNameGeneratorService,
        deps: [ConcreteProductGeneratorDataService],
    };
}
