import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { OffersListComponent } from './offers-list.component';

describe('OffersListComponent', () => {
    let component: OffersListComponent;
    let fixture: ComponentFixture<OffersListComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [OffersListComponent]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(OffersListComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
