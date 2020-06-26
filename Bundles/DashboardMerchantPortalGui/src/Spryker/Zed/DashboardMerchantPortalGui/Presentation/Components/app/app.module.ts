import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { CustomElementModule } from '@spryker/web-components';

import { DashboardModule } from './dashboard/dashboard.module';
import { DashboardComponent } from './dashboard/dashboard.component';
import { DashboardCardModule } from './dashboard-card/dashboard-card.module';
import { DashboardCardComponent } from './dashboard-card/dashboard-card.component';
import { DashboardStatsModule } from './dashboard-stats/dashboard-stats.module';
import { DashboardStatsComponent } from './dashboard-stats/dashboard-stats.component';
import { DashboardStatsBlockModule } from './dashboard-stats-block/dashboard-stats-block.module';
import { DashboardStatsBlockComponent } from './dashboard-stats-block/dashboard-stats-block.component';

@NgModule({
    imports: [
        BrowserModule,
        DashboardModule,
        DashboardCardModule,
        DashboardStatsModule,
        DashboardStatsBlockModule,
    ],
    providers: [],
})
export class AppModule extends CustomElementModule {
    protected components = [
        {
            selector: 'web-mp-dashboard',
            component: DashboardComponent,
        },
        {
            selector: 'web-mp-dashboard-card',
            component: DashboardCardComponent,
        },
        {
            selector: 'web-mp-dashboard-stats',
            component: DashboardStatsComponent,
        },
        {
            selector: 'web-mp-dashboard-stats-block',
            component: DashboardStatsBlockComponent,
        },
    ];
}
