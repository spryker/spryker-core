import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { LayoutMainComponent } from './layout-main.component';

describe('LayoutMainComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(LayoutMainComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
        projectContent: `
            <span top-section></span>
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

    describe('Components detection', () => {
        it('should render <spy-sidebar> component', async () => {
            const host = await createComponentWrapper(createComponent);
            const sidebarComponent = host.queryCss('spy-sidebar');

            expect(sidebarComponent).toBeTruthy();
        });

        it('should render <spy-header> component', async () => {
            const host = await createComponentWrapper(createComponent);
            const headerComponent = host.queryCss('spy-header');

            expect(headerComponent).toBeTruthy();
        });

        it('should render <spy-navigation> component to the <spy-sidebar> component', async () => {
            const host = await createComponentWrapper(createComponent);
            const navigationComponent = host.queryCss('spy-sidebar spy-navigation');

            expect(navigationComponent).toBeTruthy();
        });
    });

    describe('`isCollapsed` property', () => {
        it('should bound to the `collapsed` input of <spy-navigation> component', async () => {
            const host = await createComponentWrapper(createComponent);
            const navigationComponent = host.queryCss('spy-sidebar spy-navigation');

            host.component.isCollapsed = true;
            host.setInputs({ navigationConfig: '' }, true);

            expect(navigationComponent.properties.collapsed).toBe(true);
        });

        it('should change if `updateCollapseHandler` method invokes', async () => {
            const host = await createComponentWrapper(createComponent);

            host.component.updateCollapseHandler(true);
            host.detectChanges();

            expect(host.component.isCollapsed).toBe(true);
        });
    });

    describe('Slots', () => {
        it('should render `logo` slot to the `.mp-layout-main-cnt__logo` element', async () => {
            const host = await createComponentWrapper(createComponent);
            const logoSlot = host.queryCss('.mp-layout-main-cnt__logo [logo]');

            expect(logoSlot).toBeTruthy();
        });

        it('should render `top-section` slot to the `.mp-layout-main-cnt__top-section` element', async () => {
            const host = await createComponentWrapper(createComponent);
            const topSectionSlot = host.queryCss('.mp-layout-main-cnt__top-section [top-section]');

            expect(topSectionSlot).toBeTruthy();
        });

        it('should render `header` slot to the `.mp-layout-main-cnt__header` element', async () => {
            const host = await createComponentWrapper(createComponent);
            const headerSlot = host.queryCss('.mp-layout-main-cnt__header [header]');

            expect(headerSlot).toBeTruthy();
        });

        it('should render default slot to the `.mp-layout-main-cnt__content` element', async () => {
            const host = await createComponentWrapper(createComponent);
            const defaultSlot = host.queryCss('.mp-layout-main-cnt__content .default-slot');

            expect(defaultSlot).toBeTruthy();
        });
    });

    describe('@Input(navigationConfig)', () => {
        it('should bound to the `items` input of <spy-navigation> component', async () => {
            const demoData =
                '[{"title":"Dashboard","url":"\\/dashboard","icon":"fa fa-area-chart","isActive":false,"subItems":[]}]';
            const host = await createComponentWrapper(createComponent, { navigationConfig: demoData });
            const navigationComponent = host.queryCss('spy-sidebar spy-navigation');

            expect(navigationComponent.properties.items).toBe(demoData);
        });

        it('should update binding when changed', async () => {
            const demoData =
                '[{"title":"Dashboard","url":"\\/dashboard","icon":"fa fa-area-chart","isActive":false,"subItems":[]}]';
            const host = await createComponentWrapper(createComponent, { navigationConfig: demoData });
            const navigationComponent = host.queryCss('spy-sidebar spy-navigation');

            expect(navigationComponent.properties.items).toBe(demoData);

            host.setInputs({ navigationConfig: '' }, true);

            expect(navigationComponent.properties.items).toBe('');
        });
    });
});
