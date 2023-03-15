import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { MerchantLayoutCenteredComponent } from './merchant-layout-centered.component';
import { LayoutCenteredModule } from '../layout-centered/layout-centered.module';

describe('MerchantLayoutCenteredComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(MerchantLayoutCenteredComponent, {
        ngModule: {
            imports: [LayoutCenteredModule],
            schemas: [NO_ERRORS_SCHEMA],
        },
        projectContent: `
            <span footer></span>
            <span class="default-slot"></span>
        `,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render <mp-layout-centered> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const layoutCenteredComponent = host.queryCss('mp-layout-centered');

        expect(layoutCenteredComponent).toBeTruthy();
    });

    it('should render `footer` slot to the `mp-layout-centered__footer` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const footerSlot = host.queryCss('.mp-layout-centered__footer [footer]');

        expect(footerSlot).toBeTruthy();
    });

    it('should render default slot to the <mp-layout-centered> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const defaultSlot = host.queryCss('mp-layout-centered .default-slot');

        expect(defaultSlot).toBeTruthy();
    });
});
