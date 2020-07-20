import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { OffersListComponent } from './offers-list.component';
import { NO_ERRORS_SCHEMA } from '@angular/core';

describe('OffersListComponent', () => {
    let component: OffersListComponent;
    let fixture: ComponentFixture<OffersListComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [OffersListComponent],
            schemas: [NO_ERRORS_SCHEMA],
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

    it('should render `spy-headline` component', () => {
        const headlineComponent = fixture.debugElement.query(By.css('spy-headline'));

        expect(headlineComponent).toBeTruthy();
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
