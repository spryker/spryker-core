import { NgModule } from '@angular/core';
import { ButtonLinkComponent, ButtonLinkModule } from '@spryker/button';
import { ChipsComponent, ChipsModule } from '@spryker/chips';
import { CustomElementModule, WebComponentDefs } from '@spryker/web-components';

import { DashboardCardComponent } from './dashboard-card/dashboard-card.component';
import { DashboardCardModule } from './dashboard-card/dashboard-card.module';
import { DashboardStatsBlockComponent } from './dashboard-stats/dashboard-stats-block/dashboard-stats-block.component';
import { DashboardStatsComponent } from './dashboard-stats/dashboard-stats.component';
import { DashboardStatsModule } from './dashboard-stats/dashboard-stats.module';
import { DashboardComponent } from './dashboard/dashboard.component';
import { DashboardModule } from './dashboard/dashboard.module';

@NgModule({
    imports: [
        ButtonLinkModule,
        ChipsModule,
        DashboardModule,
        DashboardCardModule,
        DashboardStatsModule,
    ],
    providers: [],
})
export class ComponentsModule extends CustomElementModule {
    protected components: WebComponentDefs = [
        DashboardComponent,
        DashboardCardComponent,
        DashboardStatsComponent,
        DashboardStatsBlockComponent,
        ButtonLinkComponent,
        ChipsComponent,
    ];
}
