import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { OffersListTableComponent } from './offers-list-table.component';

describe('OffersListTableComponent', () => {
    let component: OffersListTableComponent;
    let fixture: ComponentFixture<OffersListTableComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [ OffersListTableComponent ]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(OffersListTableComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
