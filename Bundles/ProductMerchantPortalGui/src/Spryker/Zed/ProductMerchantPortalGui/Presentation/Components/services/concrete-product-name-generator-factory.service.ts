import { Inject, Injectable, Injector } from '@angular/core';
import { InjectionTokenType } from '@spryker/utils';
import { ConcreteProductNameGeneratorProviderToken, ConcreteProductNameGeneratorToken } from './tokens';
import { ConcreteProductNameGeneratorFactory } from './types';

@Injectable()
export class ConcreteProductNameGeneratorFactoryService implements ConcreteProductNameGeneratorFactory {
    constructor(
        private injector: Injector,
        @Inject(ConcreteProductNameGeneratorProviderToken)
        private concreteProductNameGeneratorProvider: InjectionTokenType<
            typeof ConcreteProductNameGeneratorProviderToken
        >,
    ) {}

    create(): InjectionTokenType<typeof ConcreteProductNameGeneratorToken> {
        const concreteProductNameGeneratorInjector = Injector.create({
            name: 'ConcreteProductNameGeneratorInjector',
            providers: [this.concreteProductNameGeneratorProvider],
            parent: this.injector,
        });

        return concreteProductNameGeneratorInjector.get(ConcreteProductNameGeneratorToken);
    }
}
