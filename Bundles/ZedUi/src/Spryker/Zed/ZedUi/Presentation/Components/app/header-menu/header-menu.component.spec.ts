import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { HeaderMenuComponent } from './header-menu.component';

describe('HeaderMenuComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(HeaderMenuComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
        projectContent: `
            <span info-primary></span>
            <span info-secondary></span>
            <div class="default-slot"></div>
        `,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render <spy-user-menu> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const userMenuComponent = host.queryCss('spy-user-menu');

        expect(userMenuComponent).toBeTruthy();
    });

    it('should render <spy-user-menu-item> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const userMenuItemComponent = host.queryCss('spy-user-menu-item');

        expect(userMenuItemComponent).toBeTruthy();
    });

    it('should render `info-primary` slot to the `.mp-header-menu__user-info-primary` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const infoPrimarySlot = host.queryCss('.mp-header-menu__user-info-primary [info-primary]');

        expect(infoPrimarySlot).toBeTruthy();
    });

    it('should render `info-secondary` slot to the `.mp-header-menu__user-info-secondary` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const infoSecondarySlot = host.queryCss('.mp-header-menu__user-info-secondary [info-secondary]');

        expect(infoSecondarySlot).toBeTruthy();
    });

    it('should render default slot to the <spy-user-menu> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const defaultSlot = host.queryCss('spy-user-menu .default-slot');

        expect(defaultSlot).toBeTruthy();
    });

    it('should render `@Input(navigationConfig)` data to the `.mp-header-menu__link` element', async () => {
        const mockConfig = [
            {
                url: 'mockUrl',
                type: 'mockType',
                title: 'mockTitle',
            },
        ];
        const host = await createComponentWrapper(createComponent, { navigationConfig: mockConfig });

        const linkElem = host.queryCss('.mp-header-menu__link');
        const userMenuLinkComponent = host.queryCss('.mp-header-menu__link spy-user-menu-link');

        expect(linkElem).toBeTruthy();
        expect(linkElem.properties.href).toBe(mockConfig[0].url);
        expect(userMenuLinkComponent).toBeTruthy();
        expect(userMenuLinkComponent.properties.type).toBe(mockConfig[0].type);
        expect(userMenuLinkComponent.nativeElement.textContent.trim()).toBe(mockConfig[0].title);
    });
});
