import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { DashboardStatsComponent } from './dashboard-stats.component';

@NgModule({
    imports: [CommonModule],
    declarations: [DashboardStatsComponent],
    exports: [DashboardStatsComponent],
})
export class DashboardStatsModule {
}
