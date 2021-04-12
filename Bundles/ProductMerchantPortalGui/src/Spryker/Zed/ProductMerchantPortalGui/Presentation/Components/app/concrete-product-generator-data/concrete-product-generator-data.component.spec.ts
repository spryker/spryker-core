import { NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { ConcreteProductGeneratorDataComponent } from './concrete-product-generator-data.component';

describe('ConcreteProductGeneratorDataComponent', () => {
    let component: ConcreteProductGeneratorDataComponent;
    let fixture: ComponentFixture<ConcreteProductGeneratorDataComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [ConcreteProductGeneratorDataComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(ConcreteProductGeneratorDataComponent);
        component = fixture.componentInstance;
    });

    it('`getAbstractName` method should return value from `@Input(abstractName)`', () => {
        const mockAbstractName = 'AbstractName';

        component.abstractName = mockAbstractName;
        fixture.detectChanges();

        expect(component.getAbstractName()).toEqual(mockAbstractName);
    });

    it('`getAbstractSku` method should return value from `@Input(abstractSku)`', () => {
        const mockAbstractSku = 'AbstractSku';

        component.abstractSku = mockAbstractSku;
        fixture.detectChanges();

        expect(component.getAbstractSku()).toEqual(mockAbstractSku);
    });
});
