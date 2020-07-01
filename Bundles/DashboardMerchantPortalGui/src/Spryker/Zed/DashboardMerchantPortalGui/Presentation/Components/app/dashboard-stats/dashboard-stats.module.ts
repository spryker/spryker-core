import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CardModule } from '@spryker/card';

import { DashboardStatsComponent } from './dashboard-stats.component';
import { DashboardStatsBlockComponent } from './dashboard-stats-block/dashboard-stats-block.component';

@NgModule({
    imports: [CommonModule, CardModule],
    declarations: [DashboardStatsComponent, DashboardStatsBlockComponent],
    exports: [DashboardStatsComponent, DashboardStatsBlockComponent],
})
export class DashboardStatsModule {
}
