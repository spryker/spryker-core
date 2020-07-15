import { NgModule } from '@angular/core';
import { ButtonLinkComponent, ButtonLinkModule } from '@spryker/button';
import { ChipsComponent, ChipsModule } from '@spryker/chips';
import { CustomElementModule } from '@spryker/web-components';

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
    protected components = [
        {
            selector: 'mp-dashboard',
            component: DashboardComponent,
        },
        {
            selector: 'mp-dashboard-card',
            component: DashboardCardComponent,
        },
        {
            selector: 'mp-dashboard-stats',
            component: DashboardStatsComponent,
        },
        {
            selector: 'mp-dashboard-stats-block',
            component: DashboardStatsBlockComponent,
        },
        {
            selector: 'spy-button-link',
            component: ButtonLinkComponent,
        },
        {
            selector: 'spy-chips',
            component: ChipsComponent,
        },
    ];
}
