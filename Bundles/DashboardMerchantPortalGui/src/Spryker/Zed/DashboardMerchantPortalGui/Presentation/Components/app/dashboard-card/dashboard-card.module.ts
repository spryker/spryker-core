import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { DashboardCardComponent } from './dashboard-card.component';

@NgModule({
    imports: [CommonModule],
    declarations: [DashboardCardComponent],
    exports: [DashboardCardComponent],
})
export class DashboardCardModule {
}
