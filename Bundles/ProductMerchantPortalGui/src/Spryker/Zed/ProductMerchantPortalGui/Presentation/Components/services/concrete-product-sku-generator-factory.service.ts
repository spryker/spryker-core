import { Inject, Injectable, Injector } from '@angular/core';
import { InjectionTokenType } from '@spryker/utils';
import { ConcreteProductSkuGeneratorProviderToken, ConcreteProductSkuGeneratorToken } from './tokens';
import { ConcreteProductSkuGeneratorFactory } from './types';

@Injectable({ providedIn: 'root' })
export class ConcreteProductSkuGeneratorFactoryService implements ConcreteProductSkuGeneratorFactory {
    constructor(
        private injector: Injector,
        @Inject(ConcreteProductSkuGeneratorProviderToken)
        private concreteProductSkuGeneratorProvider: InjectionTokenType<typeof ConcreteProductSkuGeneratorProviderToken>,
        @Inject(ConcreteProductSkuGeneratorToken)
        private concreteProductSkuGenerator: InjectionTokenType<typeof ConcreteProductSkuGeneratorToken>,
    ) {}

    create(): InjectionTokenType<typeof ConcreteProductSkuGeneratorToken> {
        const concreteProductSkuGeneratorInjector = Injector.create({
            name: 'ConcreteProductSkuGeneratorInjector',
            providers: [this.concreteProductSkuGeneratorProvider],
            parent: this.injector,
        });

        return concreteProductSkuGeneratorInjector.get(this.concreteProductSkuGenerator);
    }
}
