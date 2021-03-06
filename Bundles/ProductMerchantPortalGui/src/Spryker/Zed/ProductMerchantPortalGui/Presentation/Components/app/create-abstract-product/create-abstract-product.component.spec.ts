import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { CreateAbstractProductComponent } from './create-abstract-product.component';

@Component({
    selector: 'spy-test',
    template: `
        <mp-create-abstract-product>
            <span title>Name</span>
            <span action>Button</span>
            <div class="content">Content</div>
        </mp-create-abstract-product>
    `,
})
class TestComponent {}

describe('CreateAbstractProductComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [CreateAbstractProductComponent, TestComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
    });

    it('should render <spy-headline> component', () => {
        const headlineElem = fixture.debugElement.query(By.css('spy-headline'));

        expect(headlineElem).toBeTruthy();
    });

    it('should render `title` slot to the `.mp-create-abstract-product__header` element', () => {
        const titleSlot = fixture.debugElement.query(By.css('.mp-create-abstract-product__header [title]'));

        expect(titleSlot).toBeTruthy();
    });

    it('should render `action` slot to the `.mp-create-abstract-product__header` element', () => {
        const actionSlot = fixture.debugElement.query(By.css('.mp-create-abstract-product__header [action]'));

        expect(actionSlot).toBeTruthy();
    });

    it('should render default slot to the `.mp-create-abstract-product__content` element', () => {
        const contentSlot = fixture.debugElement.query(By.css('.mp-create-abstract-product__content .content'));

        expect(contentSlot).toBeTruthy();
    });
});
