import { NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormComponent } from './form.component';
import { By } from '@angular/platform-browser';

describe('FormComponent', () => {
    let component: FormComponent;
    let fixture: ComponentFixture<FormComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [FormComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(FormComponent);
        component = fixture.componentInstance;
    });

    it('should render `form` component', () => {
        const formComponent = fixture.debugElement.query(By.css('form'));

        expect(formComponent).toBeTruthy();
    });

    it('should bind @Input(method) to `method` of `form` component', () => {
        const mockMethod = 'mockMethod';
        const formComponent = fixture.debugElement.query(By.css('form'));

        component.method = mockMethod;
        fixture.detectChanges();

        expect(formComponent.properties.method).toBe(mockMethod);
    });

    it('should bind @Input(action) to `action` of `form` component', () => {
        const mockAction = 'mockAction';
        const formComponent = fixture.debugElement.query(By.css('form'));

        component.action = mockAction;
        fixture.detectChanges();

        expect(formComponent.properties.action).toBe(mockAction);
    });

    it('should bind @Input(name) to `name` of `form` component', () => {
        const mockName = 'mockName';
        const formComponent = fixture.debugElement.query(By.css('form'));

        component.name = mockName;
        fixture.detectChanges();

        expect(formComponent.properties.name).toBe(mockName);
    });

    it('should bind @Input(attrs) to `spyApplyAttrs` of `form` component', () => {
        const mockAttrs = {
            mock: 'mockValue'
        };
        const formComponent = fixture.debugElement.query(By.css('form'));

        component.attrs = mockAttrs;
        fixture.detectChanges();

        expect(formComponent.properties.spyApplyAttrs).toEqual(mockAttrs);
    });

    it('should bind @Input(attrs) to `spyApplyAttrs` of `form` component', () => {
        const mockMonitor = true;
        const formComponent = fixture.debugElement.query(By.css('form'));

        component.withMonitor = mockMonitor;
        fixture.detectChanges();

        expect(formComponent.properties.spyUnsavedChangesFormMonitor).toBe(mockMonitor);
    });
});
