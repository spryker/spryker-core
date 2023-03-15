import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { ManageOrderComponent } from './manage-order.component';

describe('ManageOrderComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(ManageOrderComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
        projectContent: `
            <span state-transitions></span>
            <span state-transitions-message></span>
            <span items-states></span>
            <span items-states-title></span>
            <span class="default-slot"></span>
        `,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render component with `mp-manage-order` host class name', async () => {
        const host = await createComponentWrapper(createComponent);
        const manageOrderElem = host.queryCss('.mp-manage-order');

        expect(manageOrderElem).toBeTruthy();
    });

    it('should render `state-transitions` slot to the `.mp-manage-order__heading-col--actions` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const stateTransitionsSlot = host.queryCss('.mp-manage-order__heading-col--actions [state-transitions]');

        expect(stateTransitionsSlot).toBeTruthy();
    });

    it('should render `state-transitions-message` slot to the `.mp-manage-order__transitions-message` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const stateTransitionsMessageSlot = host.queryCss(
            '.mp-manage-order__transitions-message [state-transitions-message]',
        );

        expect(stateTransitionsMessageSlot).toBeTruthy();
    });

    it('should render `items-states-title` slot to the `.mp-manage-order__states-col--title` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const itemsStatesTitleSlot = host.queryCss('.mp-manage-order__states-col--title [items-states-title]');

        expect(itemsStatesTitleSlot).toBeTruthy();
    });

    it('should render `items-states` slot to the `.mp-manage-order__states-col:last-child` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const itemsStatesSlot = host.queryCss('.mp-manage-order__states-col:last-child [items-states]');

        expect(itemsStatesSlot).toBeTruthy();
    });

    it('should render default slot after the `.mp-manage-order__information` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const defaultSlot = host.queryCss('.mp-manage-order__information + .default-slot');

        expect(defaultSlot).toBeTruthy();
    });

    it('should render `@Input(orderDetails)` data to the appropriate places', async () => {
        const mockOrderDetails = {
            title: 'title',
            referenceTitle: 'referenceTitle',
            reference: 'reference',
        };
        const host = await createComponentWrapper(createComponent, { orderDetails: mockOrderDetails });
        const titleElem = host.queryCss('.mp-manage-order__title');
        const referenceTitleElem = host.queryCss('.mp-manage-order__reference-title');
        const referenceElem = host.queryCss('.mp-manage-order__reference');

        expect(titleElem.nativeElement.textContent).toContain(mockOrderDetails.title);
        expect(referenceTitleElem.nativeElement.textContent).toContain(mockOrderDetails.referenceTitle);
        expect(referenceElem.nativeElement.textContent).toContain(mockOrderDetails.reference);
    });
});
