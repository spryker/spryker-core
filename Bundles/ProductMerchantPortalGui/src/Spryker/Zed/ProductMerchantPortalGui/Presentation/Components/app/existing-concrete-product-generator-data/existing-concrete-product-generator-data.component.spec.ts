import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { ExistingConcreteProductGeneratorDataComponent } from './existing-concrete-product-generator-data.component';

describe('ExistingConcreteProductGeneratorDataComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(ExistingConcreteProductGeneratorDataComponent, {
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

    it('`getExistingProducts` method should return value from `@Input(existingProducts)`', async () => {
        const expectedExistingProducts = [
            {
                name: '',
                sku: '',
                superAttributes: [
                    {
                        name: 'name1',
                        value: 'value1',
                        attribute: {
                            name: 'name12',
                            value: 'value12',
                        },
                    },
                    {
                        name: 'name2',
                        value: 'value2',
                        attribute: {
                            name: 'name21',
                            value: 'value21',
                        },
                    },
                ],
            },
        ];
        const host = await createComponentWrapper(createComponent, { existingProducts: expectedExistingProducts });

        expect(host.component.getExistingProducts()).toEqual(expectedExistingProducts);
    });
});
