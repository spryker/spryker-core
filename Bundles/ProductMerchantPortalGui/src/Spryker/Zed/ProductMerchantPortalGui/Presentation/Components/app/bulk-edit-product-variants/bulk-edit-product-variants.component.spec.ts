import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { CardModule } from '@spryker/card';
import { BulkEditProductVariantsComponent } from './bulk-edit-product-variants.component';
import { BulkEditProductVariantSections } from './types';

@Component({
    selector: 'mp-test',
    template: `
        <mp-bulk-edit-product-variants [sections]="sections" [notificationText]="notificationText">
            <span title class="projected-title"></span>
            <span action class="projected-action"></span>
            <span notification class="projected-notification"></span>
        </mp-bulk-edit-product-variants>
    `,
})
class TestComponent {
    sections?: Partial<BulkEditProductVariantSections>;
    notificationText?: string;
}

const mockStatusSection = {
    status: {
        title: 'Status',
        activationName: 'status_activationName',
        name: 'statuus_name',
        placeholder: 'Active',
    },
};

const mockValiditySection = {
    validity: {
        title: 'Validity Dates & Time',
        activationName: 'validity_activationName',
        name: {
            from: 'validity_formName',
            to: 'validity_toName',
        },
        placeholder: {
            from: 'formPlacehholder',
            to: 'toPlacehholder',
        },
    },
};

describe('BulkEditProductVariantsComponent', () => {
    const getTitleComponent = () => fixture.debugElement.query(By.css('spy-card spy-checkbox'));
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [CardModule],
            declarations: [BulkEditProductVariantsComponent, TestComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
    });

    it('should render default projected title in the `spy-headline` element', () => {
        const projectedTitle = fixture.debugElement.query(By.css('spy-headline .projected-title'));

        expect(projectedTitle).toBeTruthy();
    });

    it('should render default projected action in the `spy-headline` element', () => {
        const projectedAction = fixture.debugElement.query(By.css('spy-headline .projected-action'));

        expect(projectedAction).toBeTruthy();
    });

    it('should render projected notification in the `mp-bulk-edit-product-variants__content` element', () => {
        const notificationComponent = fixture.debugElement.query(
            By.css('.mp-bulk-edit-product-variants__content .projected-notification'),
        );

        expect(notificationComponent).toBeTruthy();
    });

    describe('Status section', () => {
        const getToggleComponent = () => fixture.debugElement.query(By.css('spy-card spy-toggle'));

        it('should render `spy-card spy-toggle` if `sections.status` exist', () => {
            component.sections = mockStatusSection;
            fixture.detectChanges();

            expect(getToggleComponent()).toBeTruthy();
        });

        it('should render `spy-checkbox` if `sections.status` exist', () => {
            component.sections = mockStatusSection;
            fixture.detectChanges();

            expect(getTitleComponent()).toBeTruthy();
        });

        it('should render `sections.status.placeholder` inside `spy-toggle` component', () => {
            component.sections = mockStatusSection;
            fixture.detectChanges();

            expect(getToggleComponent().nativeElement.textContent).toContain(mockStatusSection.status.placeholder);
        });

        it('should bound `sections.status.name` to the `name` property of the `spy-toggle` component', () => {
            component.sections = mockStatusSection;
            fixture.detectChanges();

            expect(getToggleComponent().properties.name).toBe(mockStatusSection.status.name);
        });

        it('should bound `true` to the `disabled` property of the `spy-toggle` component by default', () => {
            component.sections = mockStatusSection;
            fixture.detectChanges();

            expect(getToggleComponent().properties.disabled).toBe(true);
        });

        it('should render `sections.status.title` inside `spy-checkbox` component', () => {
            component.sections = mockStatusSection;
            fixture.detectChanges();

            expect(getTitleComponent().nativeElement.textContent).toContain(mockStatusSection.status.title);
        });

        it('should bound `sections.status.activationName` to the `name` property of the `spy-checkbox` component', () => {
            component.sections = mockStatusSection;
            fixture.detectChanges();

            expect(getTitleComponent().properties.name).toBe(mockStatusSection.status.activationName);
        });

        it('should change `spy-toggle` `disabled` property if `checkedChange` event of the `spy-checkbox` component has been called', () => {
            component.sections = mockStatusSection;
            fixture.detectChanges();

            expect(getToggleComponent().properties.disabled).toBe(true);

            getTitleComponent().triggerEventHandler('checkedChange', true);
            fixture.detectChanges();

            expect(getToggleComponent().properties.disabled).toBe(false);

            getTitleComponent().triggerEventHandler('checkedChange', false);
            fixture.detectChanges();

            expect(getToggleComponent().properties.disabled).toBe(true);
        });

        it('should reset `spy-toggle` `value` property if `checkedChange` event of the `spy-checkbox` component has been called with `false`', () => {
            component.sections = mockStatusSection;
            fixture.detectChanges();

            getTitleComponent().triggerEventHandler('checkedChange', true);
            fixture.detectChanges();
            getToggleComponent().triggerEventHandler('valueChange', true);
            fixture.detectChanges();

            expect(getToggleComponent().properties.value).toBe(true);

            getTitleComponent().triggerEventHandler('checkedChange', false);
            fixture.detectChanges();

            expect(getToggleComponent().properties.value).toBe(false);
        });
    });

    describe('Validity section', () => {
        const getRangeComponent = () => fixture.debugElement.query(By.css('spy-card spy-date-range-picker'));

        it('should render `spy-card spy-date-range-picker` if `sections.validity` exist', () => {
            component.sections = mockValiditySection;
            fixture.detectChanges();

            expect(getRangeComponent()).toBeTruthy();
        });

        it('should render `spy-checkbox` if `sections.status` exist', () => {
            component.sections = mockValiditySection;
            fixture.detectChanges();

            expect(getTitleComponent()).toBeTruthy();
        });

        it('should bound `sections.validity.name.to` to `nameTo` of the `spy-date-range-picker` component', () => {
            component.sections = mockValiditySection;
            fixture.detectChanges();

            expect(getRangeComponent().properties.nameTo).toContain(mockValiditySection.validity.name.to);
        });

        it('should bound `sections.validity.name.from` to `nameFrom` of the `spy-date-range-picker` component', () => {
            component.sections = mockValiditySection;
            fixture.detectChanges();

            expect(getRangeComponent().properties.nameFrom).toContain(mockValiditySection.validity.name.from);
        });

        it('should bound `sections.validity.placeholder.to` to `placeholderTo` of the `spy-date-range-picker` component', () => {
            component.sections = mockValiditySection;
            fixture.detectChanges();

            expect(getRangeComponent().properties.placeholderTo).toContain(mockValiditySection.validity.placeholder.to);
        });

        it('should bound `sections.validity.placeholder.from` to `placeholderFrom` of the `spy-date-range-picker` component', () => {
            component.sections = mockValiditySection;
            fixture.detectChanges();

            expect(getRangeComponent().properties.placeholderFrom).toContain(
                mockValiditySection.validity.placeholder.from,
            );
        });

        it('should bound `true` to the `disabled` property of the `spy-date-range-picker` component by default', () => {
            component.sections = mockValiditySection;
            fixture.detectChanges();

            expect(getRangeComponent().properties.disabled).toBe(true);
        });

        it('should render `sections.validity.title` inside `spy-checkbox` component', () => {
            component.sections = mockValiditySection;
            fixture.detectChanges();

            expect(getTitleComponent().nativeElement.textContent).toContain(mockValiditySection.validity.title);
        });

        it('should bound `sections.validity.activationName` to the `name` property of the `spy-checkbox` component', () => {
            component.sections = mockValiditySection;
            fixture.detectChanges();

            expect(getTitleComponent().properties.name).toBe(mockValiditySection.validity.activationName);
        });

        it('should change `spy-date-range-picker` `disabled` property if `checkedChange` event of the `spy-checkbox` component has been called', () => {
            component.sections = mockValiditySection;
            fixture.detectChanges();

            expect(getRangeComponent().properties.disabled).toBe(true);

            getTitleComponent().triggerEventHandler('checkedChange', true);
            fixture.detectChanges();

            expect(getRangeComponent().properties.disabled).toBe(false);

            getTitleComponent().triggerEventHandler('checkedChange', false);
            fixture.detectChanges();

            expect(getRangeComponent().properties.disabled).toBe(true);
        });

        it('should reset `spy-date-range-picker` `value` property if `checkedChange` event of the `spy-checkbox` component has been called with `false`', () => {
            const mockDates = {
                from: 'from',
                to: 'to',
            };

            component.sections = mockValiditySection;
            fixture.detectChanges();

            getTitleComponent().triggerEventHandler('checkedChange', true);
            fixture.detectChanges();
            getRangeComponent().triggerEventHandler('datesChange', mockDates);
            fixture.detectChanges();

            expect(getRangeComponent().properties.dates).toEqual(mockDates);

            getTitleComponent().triggerEventHandler('checkedChange', false);
            fixture.detectChanges();

            expect(getRangeComponent().properties.dates).toEqual({});
        });
    });
});
