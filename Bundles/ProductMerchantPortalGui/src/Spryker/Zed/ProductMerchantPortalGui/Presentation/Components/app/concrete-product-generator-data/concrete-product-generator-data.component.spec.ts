import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { ConcreteProductGeneratorDataComponent } from './concrete-product-generator-data.component';

describe('ConcreteProductGeneratorDataComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(ConcreteProductGeneratorDataComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('`getAbstractName` method should return value from `@Input(abstractName)`', async () => {
        const expectedAbstractName = 'AbstractName';
        const host = await createComponentWrapper(createComponent, { abstractName: expectedAbstractName });

        expect(host.component.getAbstractName()).toEqual(expectedAbstractName);
    });

    it('`getAbstractSku` method should return value from `@Input(abstractSku)`', async () => {
        const expectedAbstractSku = 'AbstractSku';
        const host = await createComponentWrapper(createComponent, { abstractSku: expectedAbstractSku });

        expect(host.component.getAbstractSku()).toEqual(expectedAbstractSku);
    });
});
