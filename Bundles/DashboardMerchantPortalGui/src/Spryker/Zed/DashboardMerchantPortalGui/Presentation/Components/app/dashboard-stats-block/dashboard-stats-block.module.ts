import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { DashboardStatsBlockComponent } from './dashboard-stats-block.component';

@NgModule({
    imports: [CommonModule],
    declarations: [DashboardStatsBlockComponent],
    exports: [DashboardStatsBlockComponent],
})
export class DashboardStatsBlockModule {
}
