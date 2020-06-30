import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CardModule } from '@spryker/card';

import { DashboardStatsComponent } from './dashboard-stats.component';

@NgModule({
    imports: [CommonModule, CardModule],
    declarations: [DashboardStatsComponent],
    exports: [DashboardStatsComponent],
})
export class DashboardStatsModule {
}
