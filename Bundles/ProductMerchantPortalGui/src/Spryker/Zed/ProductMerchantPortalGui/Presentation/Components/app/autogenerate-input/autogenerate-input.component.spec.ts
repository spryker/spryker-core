import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { AutogenerateInputComponent } from './autogenerate-input.component';

@Component({
    selector: 'spy-test',
    template: `
        <mp-autogenerate-input
            [name]="name"
            [value]="value"
            [placeholder]="placeholder"
            [isAutogenerate]="isAutogenerate"
            [error]="error"
            [checkboxName]="checkboxName"
            [isFieldHasHalfWidth]="isFieldHasHalfWidth"
        >
            Content
        </mp-autogenerate-input>
    `,
})
class TestComponent {
    name: string;
    value: string;
    placeholder: string;
    isAutogenerate: boolean;
    error: string;
    checkboxName: string;
    isFieldHasHalfWidth: boolean;
}

describe('AutogenerateInputComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [AutogenerateInputComponent, TestComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
    });

    it('should render <spy-form-item> component', () => {
        const formItemElem = fixture.debugElement.query(By.css('spy-form-item'));

        expect(formItemElem).toBeTruthy();
    });

    it('should render <spy-input> component in slot [control] of <spy-form-item>', () => {
        const inputElem = fixture.debugElement.query(By.css('spy-form-item [control] spy-input'));

        expect(inputElem).toBeTruthy();
    });

    it('should render <spy-checkbox> component', () => {
        const checkboxElem = fixture.debugElement.query(By.css('spy-checkbox'));

        expect(checkboxElem).toBeTruthy();
    });

    it('should render default content inside the <spy-checkbox> component', () => {
        const checkboxElem = fixture.debugElement.query(By.css('spy-checkbox'));

        expect(checkboxElem.nativeElement.textContent).toContain('Content');
    });

    it('should render hidden input if `@Input(isAutogenerate)` is true', () => {
        component.isAutogenerate = true;
        fixture.detectChanges();

        const hiddenInputElem = fixture.debugElement.query(By.css('input[type=hidden]'));

        expect(hiddenInputElem).toBeTruthy();
    });

    it('should disable <spy-input> when <spy-checkbox> is checked', () => {
        const checkboxElem = fixture.debugElement.query(By.css('spy-checkbox'));
        const inputElem = fixture.debugElement.query(By.css('spy-input'));

        component.isAutogenerate = false;
        fixture.detectChanges();

        expect(inputElem.properties.disabled).toBe(false);

        checkboxElem.triggerEventHandler('checkedChange', true);
        fixture.detectChanges();

        expect(inputElem.properties.disabled).toBe(true);
    });

    it('should change <spy-input> value to initial `@Input(value)` when <spy-checkbox> is checked', () => {
        const mockValue = 'Value';
        const mockNewValue = 'NewValue';
        const checkboxElem = fixture.debugElement.query(By.css('spy-checkbox'));
        const inputElem = fixture.debugElement.query(By.css('spy-input'));

        component.value = mockValue;
        fixture.detectChanges();
        inputElem.triggerEventHandler('valueChange', mockNewValue);
        fixture.detectChanges();

        expect(inputElem.properties.value).toBe(mockNewValue);

        checkboxElem.triggerEventHandler('checkedChange', true);
        fixture.detectChanges();

        expect(inputElem.properties.value).toBe(mockValue);
    });

    describe('@Inputs', () => {
        it('should bound `@Input(name)` to the `name` input of <spy-input> component', () => {
            const mockName = 'Name';
            const inputElem = fixture.debugElement.query(By.css('spy-input'));

            component.name = mockName;
            fixture.detectChanges();

            expect(inputElem.properties.name).toBe(mockName);
        });

        it('should bound `@Input(value)` to the `value` input of <spy-input> component', () => {
            const mockValue = 'Value';
            const inputElem = fixture.debugElement.query(By.css('spy-input'));

            component.value = mockValue;
            fixture.detectChanges();

            expect(inputElem.properties.value).toBe(mockValue);
        });

        it('should bound `@Input(placeholder)` to the `placeholder` input of <spy-input> component', () => {
            const mockPlaceholder = 'Placeholder';
            const inputElem = fixture.debugElement.query(By.css('spy-input'));

            component.placeholder = mockPlaceholder;
            fixture.detectChanges();

            expect(inputElem.properties.placeholder).toBe(mockPlaceholder);
        });

        it('should bound `@Input(isAutogenerate)` to the `disabled` input of <spy-input> component', () => {
            const inputElem = fixture.debugElement.query(By.css('spy-input'));

            component.isAutogenerate = true;
            fixture.detectChanges();

            expect(inputElem.properties.disabled).toBe(true);
        });

        it('should bound `@Input(isAutogenerate)` to the `checked` input of <spy-checkbox> component', () => {
            const checkboxElem = fixture.debugElement.query(By.css('spy-checkbox'));

            component.isAutogenerate = true;
            fixture.detectChanges();

            expect(checkboxElem.properties.checked).toBe(true);
        });

        it('should bound `@Input(checkboxName)` to the `name` input of <spy-checkbox> component', () => {
            const mockCheckboxName = 'checkboxName';
            const checkboxElem = fixture.debugElement.query(By.css('spy-checkbox'));

            component.checkboxName = mockCheckboxName;
            fixture.detectChanges();

            expect(checkboxElem.properties.name).toBe(mockCheckboxName);
        });

        it('should bound `@Input(error)` to the `error` input of <spy-form-item> component', () => {
            const mockError = 'Error';
            const formItemElem = fixture.debugElement.query(By.css('spy-form-item'));

            component.error = mockError;
            fixture.detectChanges();

            expect(formItemElem.properties.error).toBe(mockError);
        });

        it('should add `mp-autogenerate-input--half-width` class to the component if @Input(isFieldHasHalfWidth) is true', () => {
            component.isFieldHasHalfWidth = true;
            fixture.detectChanges();

            const componentElem = fixture.debugElement.query(
                By.css('.mp-autogenerate-input.mp-autogenerate-input--half-width'),
            );

            expect(componentElem).toBeTruthy();
        });
    });
});
