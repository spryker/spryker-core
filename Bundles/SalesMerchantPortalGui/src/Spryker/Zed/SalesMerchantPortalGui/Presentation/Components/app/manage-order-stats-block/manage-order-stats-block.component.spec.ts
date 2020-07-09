import { By } from '@angular/platform-browser';
import { CardModule } from '@spryker/card';

import { ManageOrderStatsBlockComponent } from './manage-order-stats-block.component';

describe('DashboardStatsComponent', () => {
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

    it('should render @Input(count) in the `.mp-dashboard-stats-block__text--count` element', () => {
        const mockCount = 'count';

        component.count = mockCount;
        fixture.detectChanges();

        const countContentElement = fixture.debugElement.query(By.css('.mp-dashboard-stats-block__text--count'));

        expect(countContentElement.nativeElement.textContent).toContain(mockCount);
    });

    it('should render @Input(name) in the `.mp-dashboard-stats-block__text:last-child` element', () => {
        const mockName = 'name';

        component.name = mockName;
        fixture.detectChanges();

        const nameContentElement = fixture.debugElement.query(By.css('.mp-dashboard-stats-block__text:last-child'));

        expect(nameContentElement.nativeElement.textContent).toContain(mockName);
    });
});
