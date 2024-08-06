import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { PaymentComponent } from './payment.component';

describe('PaymentComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(PaymentComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
        projectContent: `
            <span status></span>
            <span footer></span>
            <span class="default-slot"></span>
        `,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render `status` slot to the `.mp-payment__status` div', async () => {
        const host = await createComponentWrapper(createComponent);
        const statusSlot = host.queryCss('.mp-payment__status [status]');

        expect(statusSlot).toBeTruthy();
    });

    it('should render `footer` slot to the `.mp-payment__footer`', async () => {
        const host = await createComponentWrapper(createComponent);
        const footerSlot = host.queryCss('.mp-payment__footer [footer]');

        expect(footerSlot).toBeTruthy();
    });

    it('should render default slot to the `mp-payment__action` div', async () => {
        const host = await createComponentWrapper(createComponent);
        const defaultSlot = host.queryCss('.mp-payment__action .default-slot');

        expect(defaultSlot).toBeTruthy();
    });
});
