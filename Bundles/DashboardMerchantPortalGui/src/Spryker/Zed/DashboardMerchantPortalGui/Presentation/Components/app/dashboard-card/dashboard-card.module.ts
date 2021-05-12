import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CardModule } from '@spryker/card';
import { ChipsModule } from '@spryker/chips';

import { DashboardCardComponent } from './dashboard-card.component';

@NgModule({
    imports: [CommonModule, CardModule, ChipsModule],
    declarations: [DashboardCardComponent],
    exports: [DashboardCardComponent],
})
export class DashboardCardModule {}
