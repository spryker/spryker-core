import { NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { ExistingConcreteProductGeneratorDataComponent } from './existing-concrete-product-generator-data.component';

describe('ExistingConcreteProductGeneratorDataComponent', () => {
    let component: ExistingConcreteProductGeneratorDataComponent;
    let fixture: ComponentFixture<ExistingConcreteProductGeneratorDataComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [ExistingConcreteProductGeneratorDataComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(ExistingConcreteProductGeneratorDataComponent);
        component = fixture.componentInstance;
    });

    it('`getAbstractName` method should return value from `@Input(abstractName)`', () => {
        const expectedAbstractName = 'AbstractName';

        component.abstractName = expectedAbstractName;
        fixture.detectChanges();

        expect(component.getAbstractName()).toEqual(expectedAbstractName);
    });

    it('`getAbstractSku` method should return value from `@Input(abstractSku)`', () => {
        const expectedAbstractSku = 'AbstractSku';

        component.abstractSku = expectedAbstractSku;
        fixture.detectChanges();

        expect(component.getAbstractSku()).toEqual(expectedAbstractSku);
    });

    it('`getExistingProducts` method should return value from `@Input(existingProducts)`', () => {
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

        component.existingProducts = expectedExistingProducts;
        fixture.detectChanges();

        expect(component.getExistingProducts()).toEqual(expectedExistingProducts);
    });
});
