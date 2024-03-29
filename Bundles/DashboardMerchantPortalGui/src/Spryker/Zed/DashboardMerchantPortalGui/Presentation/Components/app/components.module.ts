import { NgModule } from '@angular/core';
import { ButtonLinkComponent, ButtonLinkModule } from '@spryker/button';
import { ChipsComponent, ChipsModule } from '@spryker/chips';
import { WebComponentsModule } from '@spryker/web-components';

import { DashboardCardComponent } from './dashboard-card/dashboard-card.component';
import { DashboardCardModule } from './dashboard-card/dashboard-card.module';
import { DashboardStatsBlockComponent } from './dashboard-stats/dashboard-stats-block/dashboard-stats-block.component';
import { DashboardStatsComponent } from './dashboard-stats/dashboard-stats.component';
import { DashboardStatsModule } from './dashboard-stats/dashboard-stats.module';
import { DashboardTableComponent } from './dashboard-table/dashboard-table.component';
import { DashboardTableModule } from './dashboard-table/dashboard-table.module';
import { DashboardComponent } from './dashboard/dashboard.component';
import { DashboardModule } from './dashboard/dashboard.module';

@NgModule({
    imports: [
        WebComponentsModule.withComponents([
            DashboardComponent,
            DashboardCardComponent,
            DashboardStatsComponent,
            DashboardStatsBlockComponent,
            ButtonLinkComponent,
            ChipsComponent,
            DashboardTableComponent,
        ]),
        ButtonLinkModule,
        ChipsModule,
        DashboardModule,
        DashboardCardModule,
        DashboardStatsModule,
        DashboardTableModule,
    ],
    providers: [],
})
export class ComponentsModule {}
