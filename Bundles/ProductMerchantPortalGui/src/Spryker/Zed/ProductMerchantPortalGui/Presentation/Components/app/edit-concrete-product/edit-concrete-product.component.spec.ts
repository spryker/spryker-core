import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { EditConcreteProductComponent } from './edit-concrete-product.component';

@Component({
    selector: 'spy-test',
    template: `
        <mp-edit-concrete-product>
            <span title></span>
            <span name></span>
            <span action></span>
            <span sub-title></span>
            <div class="default-slot"></div>
        </mp-edit-concrete-product>
    `,
})
class TestComponent {}

describe('EditConcreteProductComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [EditConcreteProductComponent, TestComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
    });

    it('should render <spy-headline> component', () => {
        const headlineComponent = fixture.debugElement.query(By.css('spy-headline'));

        expect(headlineComponent).toBeTruthy();
    });

    it('should render `title` slot to the <spy-headline> component', () => {
        const titleSlot = fixture.debugElement.query(By.css('spy-headline [title]'));

        expect(titleSlot).toBeTruthy();
    });

    it('should render `name` slot to the <spy-headline> component', () => {
        const nameSlot = fixture.debugElement.query(By.css('spy-headline [name]'));

        expect(nameSlot).toBeTruthy();
    });

    it('should render `action` to the <spy-headline> component', () => {
        const actionSlot = fixture.debugElement.query(By.css('spy-headline [action]'));

        expect(actionSlot).toBeTruthy();
    });

    it('should render `sub-title` slot to the `.mp-edit-concrete-product__header` element', () => {
        const subTitleSlot = fixture.debugElement.query(By.css('.mp-edit-concrete-product__header [sub-title]'));

        expect(subTitleSlot).toBeTruthy();
    });

    it('should render default slot to the `.mp-edit-concrete-product__content` element', () => {
        const defaultSlot = fixture.debugElement.query(By.css('.mp-edit-concrete-product__content .default-slot'));

        expect(defaultSlot).toBeTruthy();
    });
});
