import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';

import { ManageOrderStatsBlockComponent } from './manage-order-stats-block.component';

describe('ManageOrderStatsBlockComponent', () => {
    let component: ManageOrderStatsBlockComponent;
    let fixture: ComponentFixture<ManageOrderStatsBlockComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [ManageOrderStatsBlockComponent],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(ManageOrderStatsBlockComponent);
        component = fixture.componentInstance;
    });

    it('should render @Input(name) in the `.mp-manage-order-stats-block__text--name` element', () => {
        const mockName = 'name';

        component.name = mockName;
        fixture.detectChanges();

        const countContentElement = fixture.debugElement.query(By.css('.mp-manage-order-stats-block__text--name'));

        expect(countContentElement.nativeElement.textContent).toContain(mockName);
    });

    it('should render @Input(info) in the `.mp-manage-order-stats-block__text:last-child` element', () => {
        const mockInfo = 'info';

        component.info = mockInfo;
        fixture.detectChanges();

        const nameContentElement = fixture.debugElement.query(By.css('.mp-manage-order-stats-block__text:last-child'));

        expect(nameContentElement.nativeElement.textContent).toContain(mockInfo);
    });
});
