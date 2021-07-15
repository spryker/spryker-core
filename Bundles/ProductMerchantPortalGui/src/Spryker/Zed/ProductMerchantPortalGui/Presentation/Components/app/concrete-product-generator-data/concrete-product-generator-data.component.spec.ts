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
});
