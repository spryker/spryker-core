import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { ConcreteProductGeneratorDataComponent } from './concrete-product-generator-data.component';
import { By } from '@angular/platform-browser';

@Component({
    selector: 'spy-test',
    template: `
        <mp-concrete-product-generator-data [abstractName]="abstractName" [abstractSku]="abstractSku">
            Content
        </mp-concrete-product-generator-data>
    `,
})
class TestComponent {
    abstractSku: string;
    abstractName: string;
    getAbstractName = jest.fn().mockImplementation(() => this.abstractName);
    getAbstractSku = jest.fn().mockImplementation(() => this.abstractSku);
}

describe('ConcreteProductGeneratorDataComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [ConcreteProductGeneratorDataComponent, TestComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
    });

    it('`getAbstractName` method should return value from `@Input(abstractName)`', () => {
        const mockAbstractName = 'AbstractName';

        component.abstractName = mockAbstractName;
        fixture.detectChanges();
        component.getAbstractName();
        fixture.detectChanges();

        expect(component.getAbstractName).toReturnWith(mockAbstractName);
    });

    it('`getAbstractSku` method should return value from `@Input(abstractSku)`', () => {
        const mockAbstractSku = 'AbstractSku';

        component.abstractSku = mockAbstractSku;
        fixture.detectChanges();
        component.getAbstractSku();
        fixture.detectChanges();

        expect(component.getAbstractSku).toReturnWith(mockAbstractSku);
    });
});
