import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { CustomElementModule } from '@spryker/web-components';
import { ButtonLinkComponent, ButtonLinkModule } from '@spryker/button';
import { ChipsComponent, ChipsModule } from '@spryker/chips';

import { DashboardModule } from './dashboard/dashboard.module';
import { DashboardComponent } from './dashboard/dashboard.component';
import { DashboardCardModule } from './dashboard-card/dashboard-card.module';
import { DashboardCardComponent } from './dashboard-card/dashboard-card.component';
import { DashboardStatsModule } from './dashboard-stats/dashboard-stats.module';
import { DashboardStatsComponent } from './dashboard-stats/dashboard-stats.component';
import { DashboardStatsBlockComponent } from './dashboard-stats/dashboard-stats-block/dashboard-stats-block.component';

@NgModule({
    imports: [
        BrowserModule,
        ButtonLinkModule,
        ChipsModule,
        DashboardModule,
        DashboardCardModule,
        DashboardStatsModule,
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
        {
            selector: 'web-spy-button-link',
            component: ButtonLinkComponent,
        },
        {
            selector: 'web-spy-chips',
            component: ChipsComponent,
        },
    ];
}
