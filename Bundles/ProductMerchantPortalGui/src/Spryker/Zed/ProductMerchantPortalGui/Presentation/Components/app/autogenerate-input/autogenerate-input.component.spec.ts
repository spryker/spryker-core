import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { AutogenerateInputComponent } from './autogenerate-input.component';
import { By } from '@angular/platform-browser';

@Component({
    selector: 'spy-test',
    template: `
        <mp-autogenerate-input
            [name]="name"
            [value]="value"
            [placeholder]="placeholder"
            [isAutogenerate]="isAutogenerate"
            [error]="error"
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

    it('should render <spy-input> component', () => {
        const inputElem = fixture.debugElement.query(By.css('spy-input'));

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

    it('should change <spy-input> `disabled` property if `checkedChange` event of the <spy-checkbox> component has been called', () => {
        const checkboxElem = fixture.debugElement.query(By.css('spy-checkbox'));
        const inputElem = fixture.debugElement.query(By.css('spy-input'));

        component.isAutogenerate = false;
        fixture.detectChanges();

        expect(inputElem.properties.disabled).toBe(false);

        checkboxElem.triggerEventHandler('checkedChange', true);
        fixture.detectChanges();

        expect(inputElem.properties.disabled).toBe(true);
    });

    it('should change <spy-input> value to initial `@Input(value)` if `checkedChange` event of the <spy-checkbox> component has been called', () => {
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
        it('should bound `@Input(name)` to the input `name` of <spy-input> component', () => {
            const mockName = 'Name';
            const inputElem = fixture.debugElement.query(By.css('spy-input'));

            component.name = mockName;
            fixture.detectChanges();

            expect(inputElem.properties.name).toBe(mockName);
        });

        it('should bound `@Input(value)` to the input `value` of <spy-input> component', () => {
            const mockValue = 'Value';
            const inputElem = fixture.debugElement.query(By.css('spy-input'));

            component.value = mockValue;
            fixture.detectChanges();

            expect(inputElem.properties.value).toBe(mockValue);
        });

        it('should bound `@Input(placeholder)` to the input `placeholder` of <spy-input> component', () => {
            const mockPlaceholder = 'Placeholder';
            const inputElem = fixture.debugElement.query(By.css('spy-input'));

            component.placeholder = mockPlaceholder;
            fixture.detectChanges();

            expect(inputElem.properties.placeholder).toBe(mockPlaceholder);
        });

        it('should bound `@Input(isAutogenerate)` to the input `disabled` of <spy-input> component', () => {
            const mockAutogenerate = true;
            const inputElem = fixture.debugElement.query(By.css('spy-input'));

            component.isAutogenerate = mockAutogenerate;
            fixture.detectChanges();

            expect(inputElem.properties.disabled).toBe(mockAutogenerate);
        });

        it('should bound `@Input(isAutogenerate)` to the input `checked` of <spy-checkbox> component', () => {
            const mockAutogenerate = true;
            const checkboxElem = fixture.debugElement.query(By.css('spy-checkbox'));

            component.isAutogenerate = mockAutogenerate;
            fixture.detectChanges();

            expect(checkboxElem.properties.checked).toBe(mockAutogenerate);
        });

        it('should bound `@Input(error)` to the input `error` of <spy-form-item> component', () => {
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
