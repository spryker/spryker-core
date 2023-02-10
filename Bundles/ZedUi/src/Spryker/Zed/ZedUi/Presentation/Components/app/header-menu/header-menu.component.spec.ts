import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { HeaderMenuComponent } from './header-menu.component';

describe('HeaderMenuComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    @Component({
        selector: 'mp-test',
        template: `
            <mp-header-menu [navigationConfig]="navigationConfig">
                <span info-primary>Name</span>
                <span info-secondary>Email</span>

                <div class="default-slot">Content</div>
            </mp-header-menu>
        `,
    })
    class TestComponent {
        navigationConfig?: any;
    }

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [HeaderMenuComponent, TestComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;

        fixture.detectChanges();
    });

    it('should create component', () => {
        expect(component).toBeTruthy();
    });

    it('should render <spy-user-menu> component', () => {
        const menuElem = fixture.debugElement.query(By.css('spy-user-menu'));

        expect(menuElem).toBeTruthy();
    });

    it('should render <spy-user-menu-item> component', () => {
        const menuItemElem = fixture.debugElement.query(By.css('spy-user-menu-item'));

        expect(menuItemElem).toBeTruthy();
    });

    it('should render `info-primary` slot to the `.mp-header-menu__user-info-primary` element', () => {
        const infoPrimaryElem = fixture.debugElement.query(By.css('.mp-header-menu__user-info-primary [info-primary]'));

        expect(infoPrimaryElem).toBeTruthy();
        expect(infoPrimaryElem.nativeElement.textContent).toBe('Name');
    });

    it('should render `info-secondary` slot to the `.mp-header-menu__user-info-secondary` element', () => {
        const infoSecondaryElem = fixture.debugElement.query(
            By.css('.mp-header-menu__user-info-secondary [info-secondary]'),
        );

        expect(infoSecondaryElem).toBeTruthy();
        expect(infoSecondaryElem.nativeElement.textContent).toBe('Email');
    });

    it('should render default slot to the <spy-user-menu> component', () => {
        const defaultSlotElem = fixture.debugElement.query(By.css('spy-user-menu .default-slot'));

        expect(defaultSlotElem).toBeTruthy();
        expect(defaultSlotElem.nativeElement.textContent).toBe('Content');
    });

    it('should render `@Input(navigationConfig)` data to the `.mp-header-menu__link` element', () => {
        const mockConfig = [
            {
                url: 'mockUrl',
                type: 'mockType',
                title: 'mockTitle',
            },
        ];

        component.navigationConfig = mockConfig;
        fixture.detectChanges();

        const linkElem = fixture.debugElement.query(By.css('.mp-header-menu__link'));
        const userMenuLinkComponent = linkElem.query(By.css('spy-user-menu-link'));

        expect(linkElem).toBeTruthy();
        expect(linkElem.properties.href).toBe(mockConfig[0].url);
        expect(userMenuLinkComponent).toBeTruthy();
        expect(userMenuLinkComponent.properties.type).toBe(mockConfig[0].type);
        expect(userMenuLinkComponent.nativeElement.textContent.trim()).toBe(mockConfig[0].title);
    });
});
