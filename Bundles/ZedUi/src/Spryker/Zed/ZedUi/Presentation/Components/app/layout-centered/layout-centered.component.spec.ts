import { TestBed } from '@angular/core/testing';
import { NO_ERRORS_SCHEMA } from '@angular/core';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { LayoutCenteredComponent } from './layout-centered.component';
import { LayoutFooterModule } from '../layout-footer/layout-footer.module';

describe('LayoutCenteredComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(LayoutCenteredComponent, {
        ngModule: {
            imports: [LayoutFooterModule],
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

    it('should render <mp-layout-footer> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const layoutFooterComponent = host.queryCss('mp-layout-footer');

        expect(layoutFooterComponent).toBeTruthy();
    });

    it('should render `footer` slot to the <mp-layout-footer> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const footerSlot = host.queryCss('mp-layout-footer [footer]');

        expect(footerSlot).toBeTruthy();
    });

    it('should render default slot to the `.mp-layout-centered__content` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const defaultSlot = host.queryCss('.mp-layout-centered__content .default-slot');

        expect(defaultSlot).toBeTruthy();
    });
});
