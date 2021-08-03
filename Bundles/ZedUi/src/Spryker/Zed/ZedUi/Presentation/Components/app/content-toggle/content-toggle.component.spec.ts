import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { ContentToggleComponent } from './content-toggle.component';

describe('ContentToggleComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    @Component({
        selector: 'spy-test',
        template: `
            <mp-content-toggle [name]="name" [isContentHidden]="isContentHidden">
                <span toggle-text></span>
                <div class="default-slot"></div>
            </mp-content-toggle>
        `,
    })
    class TestComponent {
        name: string;
        isContentHidden: boolean;
    }

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [ContentToggleComponent, TestComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;

        fixture.detectChanges();
    });

    it('should create component', () => {
        expect(component).toBeTruthy();
    });

    it('should render <spy-checkbox> component', () => {
        const checkboxComponent = fixture.debugElement.query(By.css('spy-checkbox'));

        expect(checkboxComponent).toBeTruthy();
    });

    it('should render `toggle-text` slot to the <spy-checkbox> component', () => {
        const toggleTextSlot = fixture.debugElement.query(By.css('spy-checkbox [toggle-text]'));

        expect(toggleTextSlot).toBeTruthy();
    });

    it('should render default slot to the `.mp-content-toggle__content` element', () => {
        const defaultSlot = fixture.debugElement.query(By.css('.mp-content-toggle__content .default-slot'));

        expect(defaultSlot).toBeTruthy();
    });

    it('should bind `@Input(name)` to `name` input of <spy-checkbox> component', () => {
        const mockName = 'mockName';
        const checkboxComponent = fixture.debugElement.query(By.css('spy-checkbox'));

        component.name = mockName;
        fixture.detectChanges();

        expect(checkboxComponent.properties.name).toBe(mockName);
    });

    it('should bind `@Input(isContentHidden)` to `checked` input of <spy-checkbox> component', () => {
        const checkboxComponent = fixture.debugElement.query(By.css('spy-checkbox'));

        component.isContentHidden = true;
        fixture.detectChanges();

        expect(checkboxComponent.properties.checked).toBe(true);
    });

    it('should bind `@Input(isContentHidden)` to `hidden` property of `.mp-content-toggle__content` element', () => {
        const contentElem = fixture.debugElement.query(By.css('.mp-content-toggle__content'));

        component.isContentHidden = true;
        fixture.detectChanges();

        expect(contentElem.properties.hidden).toBe(true);
    });

    it('should change `hidden` property of `.mp-content-toggle__content` element by <spy-checkbox> change', () => {
        const contentElem = fixture.debugElement.query(By.css('.mp-content-toggle__content'));
        const checkboxComponent = fixture.debugElement.query(By.css('spy-checkbox'));

        component.isContentHidden = false;
        fixture.detectChanges();

        expect(contentElem.properties.hidden).toBe(false);

        checkboxComponent.triggerEventHandler('checkedChange', true);
        fixture.detectChanges();

        expect(contentElem.properties.hidden).toBe(true);
    });
});
