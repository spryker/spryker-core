import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { OffersListComponent } from './offers-list.component';

describe('OffersListComponent', () => {
    let component: OffersListComponent;
    let fixture: ComponentFixture<OffersListComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [OffersListComponent],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(OffersListComponent);
        component = fixture.componentInstance;
    });

    it('should render `mp-offers-list-table` component', () => {
        const listTableComponent = fixture.debugElement.query(By.css('mp-offers-list-table'));

        expect(listTableComponent).toBeTruthy();
    });

    it('should render `spy-button-link` component', () => {
        const buttonLinkComponent = fixture.debugElement.query(By.css('spy-button-link'));

        expect(buttonLinkComponent).toBeTruthy();
    });

    it('should render @Input(title) inside `h1` element', () => {
        const mockTitle = 'Test Title';
        const headingContainer = fixture.debugElement.query(By.css('h1'));

        component.title = mockTitle;
        fixture.detectChanges();

        expect(headingContainer.nativeElement.textContent).toContain(mockTitle);
    });

    it('should render @Input(actionTitle) inside `spy-button-link` component', () => {
        const mockActionTitle = 'Test Title';
        const buttonLinkComponent = fixture.debugElement.query(By.css('spy-button-link'));

        component.actionTitle = mockActionTitle;
        fixture.detectChanges();

        expect(buttonLinkComponent.nativeElement.textContent).toContain(mockActionTitle);
    });

    it('should bind @Input(actionUrl) to `url` of `spy-button-link` component', () => {
        const mockActionUrl = 'Test Title';
        const buttonLinkComponent = fixture.debugElement.query(By.css('spy-button-link'));

        component.actionUrl = mockActionUrl;
        fixture.detectChanges();

        expect(buttonLinkComponent.properties.url).toBe(mockActionUrl);
    });

    it('should bind @Input(tableConfig) to `config` of `mp-offers-list-table` component', () => {
        const mockTableConfig = {
            config: 'config',
            data: 'data',
            columns: 'columns',
        } as any;
        const listTableComponent = fixture.debugElement.query(By.css('mp-offers-list-table'));

        component.tableConfig = mockTableConfig;
        fixture.detectChanges();

        expect(listTableComponent.properties.config).toEqual(mockTableConfig);
    });
});
