import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { ManageOrderComponent } from './manage-order.component';

const mockStateTransitionsClass = 'mockStateTransitionsClass';
const mockStateTransitionsMessageClass = 'mockStateTransitionsMessageClass';
const mockItemStatesClass = 'mockItemStatesClass';
const mockDefaultClass = 'mockDefaultClass';

describe('ManageOrderComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    @Component({
        selector: 'test-edit-offer',
        template: `
            <mp-manage-order [orderDetails]="orderDetails">
                <div state-transitions class='${mockStateTransitionsClass}'></div>   
                <div state-transitions-meessage class='${mockStateTransitionsMessageClass}'></div>   
                <div items-states class='${mockItemStatesClass}'></div>   
                <div class='${mockDefaultClass}'></div> 
            </mp-manage-order>
        `,
    })
    class TestComponent {
        orderDetails: any;
    }

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [ManageOrderComponent, TestComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
    });

    it('should render component with `mp-manage-order` host class', () => {
        const manageOrderElem = fixture.debugElement.query(By.css('.mp-manage-order'));

        expect(manageOrderElem).toBeTruthy();
    });

    it('should render slot [state-transitions] in the last element with `mp-manage-order__heading-col` className', () => {
        const stateTransitionsElem = fixture.debugElement.query(
            By.css(`.mp-manage-order__heading-col:first-child .${mockStateTransitionsClass}`)
        );

        expect(stateTransitionsElem).toBeTruthy();
    });

    it('should render slot [state-transitions-meessage] in the last element with `mp-manage-order__heading-col` className after [state-transitions] slot', () => {
        const stateTransitionsMessageElem = fixture.debugElement.query(
            By.css(`
                .mp-manage-order__heading-col:first-child .${mockStateTransitionsClass} + .${mockStateTransitionsMessageClass}
            `)
        );

        fixture.detectChanges();

        expect(stateTransitionsMessageElem).toBeTruthy();
    });

    it('should render slot [items-states] after `.mp-manage-order__heading` component', () => {
        const itemStatesElem = fixture.debugElement.query(By.css(`.mp-manage-order__heading + .${mockItemStatesClass}`));

        expect(itemStatesElem).toBeTruthy();
    });

    it('should render default slot after `.mp-manage-order__information` element', () => {
        const defaultSlotElem = fixture.debugElement.query(
            By.css(`.mp-manage-order__information + .${mockDefaultClass}`)
        );

        expect(defaultSlotElem).toBeTruthy();
    });

    it('should render @Input(orderDetails) data in the appropriate places', () => {
        const mockOrderDetails = {
            title: 'title',
            referenceTitle: 'referenceTitle',
            reference: 'reference',
        };
        const titleHolderElem = fixture.debugElement.query(By.css('.mp-manage-order__title'));
        const referenceTitleHolderElem = fixture.debugElement.query(By.css('.mp-manage-order__reference-title'));
        const referenceHolderElem = fixture.debugElement.query(By.css('.mp-manage-order__reference'));

        component.orderDetails = mockOrderDetails;
        fixture.detectChanges();

        expect(titleHolderElem.nativeElement.textContent).toContain(mockOrderDetails.title);
        expect(referenceTitleHolderElem.nativeElement.textContent).toContain(mockOrderDetails.referenceTitle);
        expect(referenceHolderElem.nativeElement.textContent).toContain(mockOrderDetails.reference);
    });
});
