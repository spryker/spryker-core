import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { MerchantLayoutMainComponent } from './merchant-layout-main.component';

describe('MerchantLayoutMainComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(MerchantLayoutMainComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
        projectContent: `
            <span header></span>
            <span logo></span>
            <span class="default-slot"></span>
        `,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render <mp-layout-main> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const layoutMainComponent = host.queryCss('mp-layout-main');

        expect(layoutMainComponent).toBeTruthy();
    });

    it('should render `header` slot to the <mp-layout-main> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const headerSlot = host.queryCss('mp-layout-main [header]');

        expect(headerSlot).toBeTruthy();
    });

    it('should render `logo` slot to the <mp-layout-main> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const logoSlot = host.queryCss('mp-layout-main [logo]');

        expect(logoSlot).toBeTruthy();
    });

    it('should render default slot to the <mp-layout-main> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const defaultSlot = host.queryCss('mp-layout-main .default-slot');

        expect(defaultSlot).toBeTruthy();
    });

    it('should bound `@Input(navigationConfig)` to the `navigationConfig` input of <mp-layout-main> component', async () => {
        const mockConfig =
            '[{"title":"Dashboard","url":"\\/dashboard","icon":"fa fa-area-chart","isActive":false,"subItems":[]}]';
        const host = await createComponentWrapper(createComponent, { navigationConfig: mockConfig });
        const layoutMainComponent = host.queryCss('mp-layout-main');

        expect(layoutMainComponent.properties.navigationConfig).toBe(mockConfig);
    });
});
